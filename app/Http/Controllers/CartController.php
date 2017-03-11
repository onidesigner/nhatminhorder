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

    public $cart = null;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @author vanhs
     * @desc Cap nhat so luong san pham trong gio hang
     * @param Request $request
     * @return mixed
     */
    public function updateQuantity(Request $request){
        $user = User::find(Auth::user()->id);

        if(!$user || !$user instanceof User){
            return response()->json(['success' => false, 'message' => 'User not found!']);
        }

        if($user->isCrane()){
            return response()->json(['success' => false, 'message' => 'User is crane!']);
        }

        if($user->isDisabled()){
            return response()->json(['success' => false, 'message' => 'User is disabled!']);
        }

        $item_id = $request->get('item_id');
        $shop_id = $request->get('shop_id');
        $quantity = $request->get('quantity');

        CartItem::where([
            'id' => $item_id,
            'user_id' => $user->id
        ])->update([
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
        $user = User::find(Auth::user()->id);

        if(!$user || !$user instanceof User){
            return response()->json(['success' => false, 'message' => 'User not found!']);
        }

        if($user->isCrane()){
            return response()->json(['success' => false, 'message' => 'User is crane!']);
        }

        if($user->isDisabled()){
            return response()->json(['success' => false, 'message' => 'User is disabled!']);
        }

        $user_id = $user->id;
        $item_id = $request->get('item_id');
        $shop_id = $request->get('shop_id');

        DB::beginTransaction();

        try {
            CartItem::where([
                'id' => $item_id,
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ])->delete();

            $total_shop_items = CartItem::where([
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ])->count();

            if(!$total_shop_items):
                Cart::where([
                    'shop_id' => $shop_id,
                    'user_id' => $user_id
                ])->delete();
            endif;

            DB::commit();
            return Response::json(array('success' => true));
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => 'Có lỗi...'));
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
        $user = User::find(Auth::user()->id);

        if(!$user || !$user instanceof User){
            return response()->json(['success' => false, 'message' => 'User not found!']);
        }

        if($user->isCrane()){
            return response()->json(['success' => false, 'message' => 'User is crane!']);
        }

        if($user->isDisabled()){
            return response()->json(['success' => false, 'message' => 'User is disabled!']);
        }

        $shop_id = $request->get('shop_id');
        $user_id = $user->id;

        DB::beginTransaction();

        try {
            Cart::where([
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ])->delete();

            CartItem::where([
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ])->delete();

            DB::commit();
            return Response::json(array('success' => true));
        } catch (\Exception $e) {
            DB::rollback();
            return Response::json(array('success' => false, 'message' => 'Có lỗi...'));
        }
    }

    /**
     * @author vanhs
     * @desc Cap nhat dich vu cho tung shop, trong gio hang
     * @param Request $request
     * @return mixed
     */
    public function updateService(Request $request){
        $user = User::find(Auth::user()->id);

        if(!$user || !$user instanceof User){
            return response()->json(['success' => false, 'message' => 'User not found!']);
        }

        if($user->isCrane()){
            return response()->json(['success' => false, 'message' => 'User is crane!']);
        }

        if($user->isDisabled()){
            return response()->json(['success' => false, 'message' => 'User is disabled!']);
        }

        $service = $request->get('service');
        $user_id = $user->id;
        $shop_id = $request->get('shop_id');
        $checked = $request->get('checked');

        $service_string = Cart::select('services')->where([
            'user_id' => $user_id,
            'shop_id' => $shop_id
        ])->first()->services;

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

        Cart::where([
            'user_id' => $user_id,
            'shop_id' => $shop_id
        ])->update([
            'services' => implode('|', $services)
        ]);

        return Response::json(array('success' => true));
    }

    /**
     * @author vanhs
     * @desc Update cac thong tin cua shop trong gio hang (Chi thich rieng, chu thich chung, ...)
     * @param Request $request
     * @return mixed
     */
    public function actionUpdate(Request $request){
        $user = User::find(Auth::user()->id);

        if(!$user || !$user instanceof User){
            return response()->json(['success' => false, 'message' => 'User not found!']);
        }

        if($user->isCrane()){
            return response()->json(['success' => false, 'message' => 'User is crane!']);
        }

        if($user->isDisabled()){
            return response()->json(['success' => false, 'message' => 'User is disabled!']);
        }

        $user_id = $user->id;
        $data = $request->get('data');
        $shop_id = $request->get('shop_id');
        $item_id = $request->get('item_id');

        CartItem::where([
            'user_id' => $user_id,
            'id' => $item_id
        ])->update($data);

        return Response::json(array('success' => true));
    }

    /**
     * @author vanhs
     * @desc Ham kiem tra cac dieu kien dau vao truoc khi tien hanh dat coc
     * @param User $user
     * @param $destination_warehouse
     * @param $shop_id
     * @param $address_id
     * @param $password
     * @param $deposit_amount
     * @return null|string
     */
    private function __validateBeforeDepositOrder(User $user, $destination_warehouse, $shop_id,
                                                  $address_id, $password,
                                                  $deposit_amount){
        if(!$user):
            return 'User not found!';
        endif;

        if($user->isCrane()):
            return 'Tài khoản quản trị không thể đặt cọc.';
        endif;

        if($user->isDisabled()):
            return 'Tài khoản đã ngừng hoạt động.';
        endif;

        if(!count($shop_id)):
            return 'Không tồn tại shop.';
        endif;

        $check_exists_address = UserAddress::select('id')->where([
            'user_id' => $user->id,
            'is_delete' => 0
        ])->count();

        if(!$check_exists_address):
            return 'Hiện chưa có địa chỉ nhận hàng.';
        endif;

        $check_exists_address_default = UserAddress::select('id')->where([
            'user_id' => $user->id,
            'is_default' => 1,
            'is_delete' => 0
        ])->count();

        if(!$check_exists_address_default):
            return 'Chưa có địa chỉ nhận hàng mặc định.';
        endif;

        $user_address = UserAddress::find($address_id);
        if(!$user_address):
            return 'Địa chỉ nhận hàng không hợp lệ.';
        endif;

        if(!$password || !Hash::check($password, $user->password)):
            return 'Mật khẩu không chính xác.';
        endif;

        if(!($user->account_balance >= $deposit_amount)):
            return 'Tài khoản không đủ tiền để thực hiện đặt cọc.';
        endif;

        if(empty($destination_warehouse)):
            return 'Không tìm thấy kho đích của khách.';
        endif;

        return null;
    }

    /**
     * @author vanhs
     * @desc Xu ly hanh dong dat coc don hang
     * @param Request $request
     * @return mixed
     */
    public function depositOrder(Request $request){
        try{
            $user_id = Auth::user()->id;
            $password = $request->get('password');
            $shop_id = $request->get('shop_id');
            $address_id = $request->get('address_id');
            $exchange_rate = Exchange::getExchange();
            $user = User::find($user_id);
            $destination_warehouse = $user->destination_warehouse();

            #region lay ra tong so tien hang theo danh sach shop
            $total_shop_amount = 0;
            $cart_items = CartItem::select('*')
                ->where([
                    'user_id' => $user_id
                ])
                ->whereIn('shop_id', $shop_id)
                ->get();

            if($cart_items):
                foreach($cart_items as $cart_item):
                    $price = $cart_item->getPriceCalculator();
                    $quantity = $cart_item->quantity;
                    $total_shop_amount += ($price * $quantity) * $exchange_rate;
                endforeach;
            endif;
            #endregion

            $deposit_percent = Cart::getDepositPercent();
            $deposit_amount = Cart::getDepositAmount($deposit_percent, $total_shop_amount);

            $message = $this->__validateBeforeDepositOrder($user, $destination_warehouse, $shop_id, $address_id,
                $password, $deposit_amount);

            if($message):
                return Response::json(['success' => false, 'message' => $message]);
            endif;

            $result = Cart::depositOrder($user, $destination_warehouse, $shop_id,
                $address_id, $exchange_rate,
                $deposit_percent, $deposit_amount);

            if($result){
                $redirect_url = url('cart/deposit/success');

                return Response::json(['success' => true,
                    'redirect_url' => $redirect_url,
                    'message' => 'Đặt cọc đơn thành công. Xin cám ơn!']);
            }

            return Response::json(['success' => false,
                'message' => 'Đặt cọc không thành công. Vui lòng thử lại!']);

        }catch (\Exception $e){
            return Response::json(['success' => false,
                'message' => 'Đặt cọc không thành công. Vui lòng thử lại!']);

        }
    }

    /**
     * @author vanhs
     * @desc Hien thi trang dat coc don thanh cong
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function depositSuccess(){
        $data = [
            'page_title' => 'Đặt cọc thành công'
        ];

        return view('deposit_success', $data);
    }

    /**
     * @author vanhs
     * @desc Hien thi thong tin trang dat coc don hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deposit(Request $request){
        $user_id = Auth::user()->id;
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
            $items = Cart::find($shop->id)->cart_item()->where(['user_id' => $user_id])->get();

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

        $deposit_percent = Cart::getDepositPercent();
        $deposit_amount = Cart::getDepositAmount($deposit_percent, $total_amount_shop);

        $data = [
            'page_title' => 'Đặt cọc',
            'user_address' => UserAddress::findByUserId($user_id),
            'max_user_address' => UserAddress::checkMaxUserAddress($user_id),
            'all_provinces' => Location::getAllProvinces(),
            'all_districts' => Location::getAllDistricts(),
            'shops' => $shops,
            'shop_id' => $shop_id_list,
            'total_amount_shop' => $total_amount_shop,
            'deposit_percent' => $deposit_amount,
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
        $user = User::find(Auth::user()->id);

        if(!$user || !$user instanceof User){
            return redirect('404');
        }

        if($user->isCrane()){
            return redirect('403');
        }

        if($user->isDisabled()){
            return redirect('403');
        }

        $user_id = $user->id;

        $data = [];

        $carts = Cart::where([
            'user_id' => $user_id
        ])->orderBy('updated_at', 'desc')->get();

        $cart_items = CartItem::where([
            'user_id' => $user_id
        ])->orderBy('updated_at', 'desc')->get();

        $total_shops = 0;
        $total_items = 0;
        $total_amount_vnd = 0;

        $exchange_rate = Exchange::getExchange();

        $temp = [];
        if($cart_items):
            foreach($cart_items as $key => $cart_item):
                $cart_id = $cart_item->cart_id;

                $total_amount_item = $cart_item->getPriceCalculator() * $cart_item->quantity;
                $total_amount_item_vnd = $exchange_rate * $cart_item->getPriceCalculator() * $cart_item->quantity;
                $cart_item->total_amount_item = $total_amount_item;
                $cart_item->total_amount_item_vnd = $total_amount_item_vnd;

                $cart_item->price_calculator = $cart_item->getPriceCalculator();
                $cart_item->price_calculator_vnd = $exchange_rate * $cart_item->getPriceCalculator();

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
            'page_title' => 'Giỏ hàng',
            'data' => $data
        ]);
    }
}
