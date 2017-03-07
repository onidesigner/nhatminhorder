<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Exchange;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Service;
use App\Cart;
use JavaScript;
use App\Location;

class CartController extends Controller
{
    protected $user = null;

    public $cart = null;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->middleware('auth');

        $this->cart = new Cart();
    }

    /**
     * @author vanhs
     * @desc Cap nhat so luong san pham trong gio hang
     * @param Request $request
     * @return mixed
     */
    public function updateQuantity(Request $request){
        if(Auth::user()->section == User::SECTION_CRANE):
            return Response::json(array('success' => false, 'message' => 'Khong the thuc hien hanh dong nay'));
        endif;

        $item_id = $request->get('item_id');
        $shop_id = $request->get('shop_id');
        $quantity = $request->get('quantity');

        DB::table('cart_items')
            ->where([
                'id' => $item_id,
                'user_id' => Auth::user()->id
            ])
            ->update([
                'quantity' => $quantity
            ]);

        return Response::json(array('success' => true));
    }

    /**
     * @author vanhs
     * @desc Xoa san pham trong gio hang
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function deleteItem(Request $request){
        if(Auth::user()->section == User::SECTION_CRANE):
            return Response::json(array('success' => false, 'message' => 'Khong the thuc hien hanh dong nay'));
        endif;

        $user_id = Auth::user()->id;
        $item_id = $request->get('item_id');
        $shop_id = $request->get('shop_id');

        DB::beginTransaction();

        try {
            DB::table('cart_items')->where([
                'id' => $item_id,
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ])->delete();

            $total_shop_items = DB::table('cart_items')->select('id')->where([
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ])->count();

            if(!$total_shop_items):
                DB::table('carts')->where('shop_id', $shop_id)->where('user_id', $user_id)->delete();
            endif;

            DB::commit();
            return Response::json(array('success' => true));
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return Response::json(array('success' => false, 'message' => 'Co loi...'));
        }

    }

    /**
     * @author vanhs
     * @desc Xoa shop trong gio hang
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public function deleteShop(Request $request){
        if(Auth::user()->section == User::SECTION_CRANE):
            return Response::json(array('success' => false, 'message' => 'Khong the thuc hien hanh dong nay'));
        endif;

        $shop_id = $request->get('shop_id');
        $user_id = Auth::user()->id;
        DB::beginTransaction();

        try {
            DB::table('carts')->where('shop_id', $shop_id)->where('user_id', $user_id)->delete();
            DB::table('cart_items')->where('shop_id', $shop_id)->where('user_id', $user_id)->delete();
            DB::commit();
            return Response::json(array('success' => true));
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return Response::json(array('success' => false, 'message' => 'Co loi...'));
        }
    }

    /**
     * @author vanhs
     * @desc Cap nhat dich vu cho tung shop, trong gio hang
     * @param Request $request
     * @return mixed
     */
    public function updateService(Request $request){
        if(Auth::user()->section == User::SECTION_CRANE):
            return Response::json(array('success' => false, 'message' => 'Khong the thuc hien hanh dong nay'));
        endif;

        $service = $request->get('service');
        $user_id = Auth::user()->id;
        $shop_id = $request->get('shop_id');
        $checked = $request->get('checked');
        $service_string = DB::table('carts')->select('services')->where(['user_id' => $user_id, 'shop_id' => $shop_id])->first()->services;
        $services = [];
        if($service_string){
            $services = explode('|', $service_string);
        }

        if($checked):
            if(!in_array($service, $services)):
                $services[] = $service;
            endif;
        else:
            $key = array_search($service, $services);
            unset($services[$key]);
        endif;

        DB::table('carts')
            ->where([
                'user_id' => $user_id,
                'shop_id' => $shop_id
            ])
            ->update(['services' => implode('|', $services)]);

        return Response::json(array('success' => true));
    }

    /**
     * @author vanhs
     * @desc Update cac thong tin cua shop trong gio hang (Chi thich rieng, chu thich chung, ...)
     * @param Request $request
     * @return mixed
     */
    public function actionUpdate(Request $request){
        if(Auth::user()->section == User::SECTION_CRANE):
            return Response::json(array('success' => false, 'message' => 'Khong the thuc hien hanh dong nay'));
        endif;

        $user_id = Auth::user()->id;
        $data = $request->get('data');
        $shop_id = $request->get('shop_id');
        $item_id = $request->get('item_id');

        DB::table('cart_items')
            ->where('user_id', $user_id)
            ->where('id', $item_id)
            ->update($data);

        return Response::json(array('success' => true));
    }

    public function depositOrder(Request $request){
        $user_id = Auth::user()->id;
        $password = $request->get('password');
        $shop_id = $request->get('shop_id');
        $address_id = $request->get('address_id');


        if(Auth::user()->section == User::SECTION_CRANE):
            return Response::json(['success' => false, 'message' => 'Khong the dat coc.']);
        endif;

        if(!count($shop_id)):
            return Response::json(['success' => false, 'message' => 'Khong ton tai shop.']);
        endif;

        $check_exists_address = UserAddress::select('id')->where([
            'user_id' => $user_id
        ])->count();

        if(!$check_exists_address):
            return Response::json(['success' => false, 'message' => 'Ban chua thiet lap dia chi nhan hang.']);
        endif;

        $check_exists_address_default = UserAddress::select('id')->where([
            'user_id' => $user_id,
            'is_default' => 1
        ])->count();

        if(!$check_exists_address_default):
            return Response::json(['success' => false, 'message' => 'Ban chua thiet lap dia chi nhan hang mac dinh.']);
        endif;

        $user_address = UserAddress::find($address_id);
        if(!$user_address):
            return Response::json(['success' => false, 'message' => 'Dia chi nhan hang khong hop le.']);
        endif;

        $pass = Auth::user()->password;
        if(!$password || !Hash::check($password, $pass)):
            return Response::json(['success' => false, 'message' => 'Mat khau khong hop le.']);
        endif;

        $exchange_rate = Exchange::getExchange();

        $total_shop_amount = 0;
        #region lay ra tong so tien hang theo danh sach shop
        $cart_items = CartItem::select('*')
        ->where([
            'user_id' => $user_id
        ])
        ->whereIn('shop_id', $shop_id)
        ->get();
        if($cart_items):
            foreach($cart_items as $cart_item):
                $amount = $cart_item->getPriceCalculator();
                $quantity = $cart_item->quantity;
                $total_shop_amount += $amount * $quantity * $exchange_rate;
            endforeach;
        endif;
        #endregion

        $user_account_balance = Auth::user()->account_balance;
        $deposit_amount = $this->cart->getDepositAmount($total_shop_amount);

        if(!($user_account_balance >= $deposit_amount)):
            return Response::json(['success' => false, 'message' => 'Tai khoan khong de tien de thuc hien dat coc.']);
        endif;

        $this->cart->depositOrder($user_id, $shop_id, $address_id, $deposit_amount);

        return Response::json(['success' => true, 'message' => 'Dat coc don thanh cong.']);
    }

    public function deposit(Request $request){
        $user_id = Auth::user()->id;
        $user_address = new UserAddress();
        $location = new Location();
        $exchange_rate = Exchange::getExchange();

        $shops = [];
        $shop_id = $request->get('shop_id');
        $shop_id_list = $shop_id ? explode(',', $shop_id) : [];
        if(count($shop_id_list)):
            $shops = Cart::where([
                'user_id' => $user_id,
            ])->whereIn('shop_id', $shop_id_list)
            ->get();
        endif;
        $total_amount_shop = 0;

        foreach($shops as $shop):
            $items = $shop->getItems();

            $total_quantity = 0;
            $total_amount = 0;
            $total_link = 0;

            foreach($items as $item):
                $total_link++;
                $total_quantity += $item->quantity;
                $total_amount += $item->getPriceCalculator() * $item->quantity * $exchange_rate;

            endforeach;

            $shop->items = $items;

            $shop->total_quantity = $total_quantity;
            $shop->total_amount = $total_amount;
            $shop->total_link = $total_link;

            $total_amount_shop += $total_amount;
        endforeach;

        $deposit_amount = $this->cart->getDepositAmount($total_amount_shop);

        $data = [
            'page_title' => 'Dat coc',
            'user_address' => $user_address->findByUserId($user_id),
            'all_provinces' => $location->getAllProvinces(),
            'all_districts' => $location->getAllDistricts(),
            'shops' => $shops,
            'shop_id' => $shop_id_list,
            'total_amount_shop' => $total_amount_shop,
            'deposit_percent' => $this->cart->getDepositPercent(),
            'deposit_amount' => $deposit_amount
        ];

        JavaScript::put($data);

        return view('deposit', $data);
    }

    /**
     * @author vanhs
     * @desc Lay thong tin gio hang theo tung user
     * @return array
     */
    public function showCart(){
        $user_id = Auth::user()->id;
        $data = [];
        $carts = DB::table('carts')->where('user_id', $user_id)->orderBy('updated_at', 'desc')->get()->toArray();
        $cart_items = DB::table('cart_items')->where('user_id', $user_id)->orderBy('updated_at', 'desc')->get()->toArray();

        $total_shops = 0;
        $total_items = 0;
        $total_amount_vnd = 0;

        $temp = [];
        if($cart_items):
            foreach($cart_items as $key => $cart_item):
                $cart_id = $cart_item->cart_id;
                $total_amount_item = $cart_item->quantity * $cart_item->price_promotion;
                $total_amount_item_vnd = $cart_item->quantity * $cart_item->price_vnd;
                $cart_item->total_amount_item = $total_amount_item;
                $cart_item->total_amount_item_vnd = $total_amount_item_vnd;
                $temp[$cart_id][] = $cart_item;

                $total_items += $cart_item->quantity;
                $total_amount_vnd += $total_amount_item_vnd;
            endforeach;
        endif;

        if($carts):
            foreach($carts as $cart):
                $cart_id = $cart->id;
                $items = [];
                if(!empty($temp[$cart_id])):
                    $items = $temp[$cart_id];
                endif;

                $total_amount_items = 0;
                foreach($items as $item):
                    $total_amount_items += $item->total_amount_item_vnd;
                endforeach;

                $cart->items = $items;
                $cart->total_amount_items = $total_amount_items;
                $cart->fee_temp = 0;
                $cart->total_amount_finish = $cart->total_amount_items + $cart->fee_temp;

                $services = [];
                if($cart->services):
                    $services_array = explode('|', $cart->services);
                    foreach($services_array as $k => $v):
                        $services[] = $v;
                    endforeach;
                endif;

                $cart->services = $services;
                $data['shops'][] = $cart;

                $total_shops++;
            endforeach;
        endif;

        //fixdata
        $data['services'] = [
            ['title' => 'Kiểm hàng', 'code' => Service::TYPE_CHECKING],
            ['title' => 'Đóng gỗ', 'code' => Service::TYPE_WOOD_CRATING]
        ];

        $data['statistic'] = [
            'total_shops' => $total_shops,
            'total_items' => $total_items,
            'total_amount' => $total_amount_vnd
        ];

        JavaScript::put($data);

        return view('cart', [
            'page_title' => 'Gio Hang',
            'data' => $data
        ]);
    }
}
