<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Exchange;
use App\Location;
use App\OrderFreightBill;
use App\OrderItem;
use App\OrderOriginalBill;
use App\Permission;
use App\User;
use App\UserAddress;
use App\UserOriginalSite;
use App\UserTransaction;
use App\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @author vanhs
     * @desc Them ma van don
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertFreightBill(Request $request){

        try{
            DB::beginTransaction();

            $order_id = $request->get('order_id');
            $freight_bill = $request->get('freight_bill');

            $order = Order::find($order_id);
            $user = User::find(Auth::user()->id);

            if(!$order):
                return response()->json(['success' => false, 'message' => 'Order not found!']);
            endif;

            if(!$user):
                return response()->json(['success' => false, 'message' => 'User not found!']);
            endif;

            $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_ADD_FREIGHT_BILL);
            if(!$can_execute):
                return response()->json(['success' => false, 'message' => 'Not permission!']);
            endif;

            if(empty($freight_bill)):
                return response()->json(['success' => false, 'message' => 'Mã vận đơn không để trống!']);
            endif;

            $order_freight_bill_exists = $order->has_freight_bill($freight_bill);

            if($order_freight_bill_exists):
                return response()->json(['success' => false, 'message' => sprintf('Mã hóa đơn %s đã tồn tại', $freight_bill)]);
            endif;

            $freight_bill_exists = OrderFreightBill::where([
                'freight_bill' => $freight_bill
            ])->count();

            OrderFreightBill::insert([
                'user_id' => Auth::user()->id,
                'order_id' => $order_id,
                'freight_bill' => $freight_bill,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $message = '';
            if($freight_bill_exists):
                $message = sprintf('Mã hóa đơn %s đã tồn tại ở 1 đơn hàng khác!', $freight_bill);
            endif;

            Comment::insert([
                'user_id' => $user->id,
                'object_id' => $order->id,
                'object_type' => Comment::TYPE_OBJECT_ORDER,
                'scope' => Comment::TYPE_INTERNAL,
                'message' => sprintf('Thêm mã vận đơn %s', $freight_bill),
                'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Exception!']);
        }
    }

    /**
     * @author vanhs
     * @desc Xoa ma van don
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFreightBill(Request $request){

        try{
            DB::beginTransaction();

            $order_id = $request->get('order_id');
            $freight_bill = $request->get('freight_bill');

            $order = Order::find($order_id);
            $user = User::find(Auth::user()->id);

            if(!$order):
                return response()->json(['success' => false, 'message' => 'Order not found!']);
            endif;

            if(!$user):
                return response()->json(['success' => false, 'message' => 'User not found!']);
            endif;

            $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_REMOVE_FREIGHT_BILL);
            if(!$can_execute):
                return response()->json(['success' => false, 'message' => 'Not permission!']);
            endif;

            OrderFreightBill::where([
                'order_id' => $order_id,
                'freight_bill' => $freight_bill
            ])->delete();

            Comment::insert([
                'user_id' => $user->id,
                'object_id' => $order->id,
                'object_type' => Comment::TYPE_OBJECT_ORDER,
                'scope' => Comment::TYPE_INTERNAL,
                'message' => sprintf('Xóa mã vận đơn %s', $freight_bill),
                'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'delete success']);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Exception']);
        }
    }

    /**
     * @author vanhs
     * @desc Them ma hoa don site goc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertOriginalBill(Request $request){
        try{
            DB::beginTransaction();

            $order_id = $request->get('order_id');
            $original_bill = $request->get('original_bill');

            $order = Order::find($order_id);
            $user = User::find(Auth::user()->id);

            if(!$order):
                return response()->json(['success' => false, 'message' => 'Order not found!']);
            endif;

            if(!$user):
                return response()->json(['success' => false, 'message' => 'User not found!']);
            endif;

            $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_ADD_ORIGINAL_BILL);
            if(!$can_execute):
                return response()->json(['success' => false, 'message' => 'Not permission!']);
            endif;

            if(empty($original_bill)):
                return response()->json(['success' => false, 'message' => 'Mã hóa đơn gốc không để trống!']);
            endif;

            $order_original_bill_exists = $order->has_origin_bill($original_bill);

            if($order_original_bill_exists):
                return response()->json(['success' => false, 'message' => sprintf('Mã hóa đơn gốc %s đã tồn tại!', $original_bill)]);
            endif;

            $original_bill_exists = OrderOriginalBill::where([
                'original_bill' => $original_bill
            ])->count();

            OrderOriginalBill::insert([
                'user_id' => Auth::user()->id,
                'order_id' => $order_id,
                'original_bill' => $original_bill,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            Comment::insert([
                'user_id' => $user->id,
                'object_id' => $order->id,
                'object_type' => Comment::TYPE_OBJECT_ORDER,
                'scope' => Comment::TYPE_INTERNAL,
                'message' => sprintf('Thêm mã hóa đơn gốc %s', $original_bill),
                'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $message = '';

            if($original_bill_exists):
                $message = sprintf('Mã hóa đơn gốc %s đã tồn tại ở 1 đơn hàng khách!', $original_bill);
            endif;

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Exception']);
        }
    }

    /**
     * @author vanhs
     * @desc Xoa ma hoa don site goc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeOriginalBill(Request $request){
        try{
            DB::beginTransaction();

            $order_id = $request->get('order_id');
            $user = User::find(Auth::user()->id);

            $original_bill = $request->get('original_bill');

            $order = Order::find($order_id);

            if(!$order):
                return response()->json(['success' => false, 'message' => 'Order not found!']);
            endif;

            if(!$user):
                return response()->json(['success' => false, 'message' => 'User not found!']);
            endif;

            $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_REMOVE_ORIGINAL_BILL);
            if(!$can_execute):
                return response()->json(['success' => false, 'message' => 'Not permission!']);
            endif;

            OrderOriginalBill::where([
                'order_id' => $order_id,
                'original_bill' => $original_bill
            ])->delete();

            Comment::insert([
                'user_id' => $user->id,
                'object_id' => $order->id,
                'object_type' => Comment::TYPE_OBJECT_ORDER,
                'scope' => Comment::TYPE_INTERNAL,
                'message' => sprintf('Xóa mã hóa đơn gốc %s', $original_bill),
                'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return response()->json([ 'success' => true, 'message' => 'delete success' ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Exception!']);
        }
    }

    /**
     * @author vanhs
     * @desc Danh sach don hang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOrders(){
        $exchange_rage = Exchange::getExchange();
        $per_page = 50;
        $orders = Order::select('*')
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return view('orders', [
            'page_title' => ' Quản lý đơn hàng',
            'orders' => $orders,
            'exchange_rage' => $exchange_rage
        ]);
    }

    /**
     * @author vanhs
     * @desc Chi tiet don hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getOrder(Request $request){

        $can_view = Permission::isAllow(Permission::PERMISSION_ORDER_VIEW);
        if(!$can_view):
            return redirect('403');
        endif;

        $order_id = $request->route('id');

        $order = Order::find($order_id);

        if(!$order):
            $order = Order::where(['code' => $order_id])->first();
            if(!$order):
                return redirect('404');
            endif;
        endif;

        $freight_bill = $order->freight_bill;
        $original_bill = $order->original_bill;

        $user_origin_site = UserOriginalSite::all();

        $warehouse_distribution = Warehouse::where(['type' => WareHouse::TYPE_DISTRIBUTION])
            ->orderBy('alias', 'asc')
            ->orderBy('ordering', 'asc')->get();

        $warehouse_receive = Warehouse::where(['type' => WareHouse::TYPE_RECEIVE])
            ->orderBy('alias', 'asc')
            ->orderBy('ordering', 'asc')->get();

        $order_items = $order->item;
        $transactions = UserTransaction::where([
            'object_id' => $order->id,
            'object_type' => UserTransaction::OBJECT_TYPE_ORDER,
            'state' => UserTransaction::STATE_COMPLETED
        ])->orderBy('created_at', 'desc')
            ->get();

        $user_address = UserAddress::find($order->user_address_id);
        if($user_address && $user_address instanceof UserAddress){
            $district = Location::find($user_address->district_id);
            if($district){
                $user_address->district_label = $district->label;
            }

            $province = Location::find($user_address->province_id);
            if($province){
                $user_address->province_label = $province->label;
            }
        }

        $order_item_comments_data = [];
        $order_item_comments = Comment::where([
            'parent_object_id' => $order->id,
            'parent_object_type' => Comment::TYPE_OBJECT_ORDER,
            'object_type' => Comment::TYPE_OBJECT_ORDER_ITEM,
            'scope' => Comment::TYPE_NONE,
        ])->orderBy('created_at', 'desc')->get();
        if($order_item_comments){
            foreach($order_item_comments as $order_item_comment){
                $order_item_comment->user = User::find($order_item_comment->user_id);
                $order_item_comments_data[$order_item_comment->object_id][] = $order_item_comment;
            }
        }

        return view('order_detail', [
            'order_id' => $order_id,
            'freight_bill' => $freight_bill,
            'original_bill' => $original_bill,
            'warehouse_distribution' => $warehouse_distribution,
            'warehouse_receive' => $warehouse_receive,
            'user_address' => $user_address,
            'order' => $order,
            'order_item_comments' => $order_item_comments_data,
            'user_origin_site' => $user_origin_site,
            'order_items' => $order_items,
            'transactions' => $transactions,
            'page_title' => 'Chi tiết đơn hàng',
        ]);
    }

    /**
     * @author vanhs
     * @desc Cac hanh dong tren trang chi tiet don hang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {

        try{
            DB::beginTransaction();

            $order_id = $request->route('id');
            $order = Order::find($order_id);
            $user = User::find(Auth::user()->id);
            $action = '__' . $request->get('action');

            if(!$order){
                return response()->json(['success' => false, 'message' => 'Order not found!']);
            }

            if(!$user){
                return response()->json(['success' => false, 'message' => 'User not found!']);
            }

            if (!method_exists($this, $action)) {
                return response()->json(['success' => false, 'message' => 'Not support action!']);
            }
            $this->$action($request, $order, $user);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'success']);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
        }

    }

    private function __order_item_comment(Request $request, Order $order, User $user){
        Comment::insert([
            'user_id' => $user->id,
            'parent_object_id' => $order->id,
            'parent_object_type' => Comment::TYPE_OBJECT_ORDER,
            'object_id' => $request->get('item_id'),
            'object_type' => Comment::TYPE_OBJECT_ORDER_ITEM,
            'scope' => Comment::TYPE_NONE,
            'message' => $request->get('message'),
            'type_context' => Comment::TYPE_CONTEXT_CHAT,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function __account_purchase_origin(Request $request, Order $order, User $user){
        $message = null;

        $account_old = $order->account_purchase_origin;
        $account_new = $request->get('value');

        if(empty($account_old)){
            $message = sprintf('Chon user mua hàng site gốc %s', $account_new);
        }else{
            $message = sprintf('Thay đổi user mua hàng site gốc %s -> %s', $account_old, $account_new);
        }

        Order::where([
            'id' => $order->id
        ])->update([
            'account_purchase_origin' => $account_new
        ]);

        Comment::insert([
            'user_id' => $user->id,
            'object_id' => $order->id,
            'object_type' => Comment::TYPE_OBJECT_ORDER,
            'scope' => Comment::TYPE_INTERNAL,
            'message' => $message,
            'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function __receive_warehouse(Request $request, Order $order, User $user){
        $message = null;

        $old = $order->receive_warehouse;
        $new = $request->get('value');

        if(empty($old)){
            $message = sprintf('Thiết lập kho nhận hàng %s', $new);
        }else{
            $message = sprintf('Thay đổi kho nhận hàng %s -> %s', $old, $new);
        }

        Order::where([
            'id' => $order->id
        ])->update([
            'receive_warehouse' => $new
        ]);

        Comment::insert([
            'user_id' => $user->id,
            'object_id' => $order->id,
            'object_type' => Comment::TYPE_OBJECT_ORDER,
            'scope' => Comment::TYPE_INTERNAL,
            'message' => $message,
            'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function __destination_warehouse(Request $request, Order $order, User $user){
        $message = null;

        $old = $order->destination_warehouse;
        $new = $request->get('value');

        if(empty($old)){
            $message = sprintf('Thiết lập kho phân phối %s', $new);
        }else{
            $message = sprintf('Thay đổi kho phân phối %s -> %s', $old, $new);
        }

        Order::where([
            'id' => $order->id
        ])->update([
            'destination_warehouse' => $new
        ]);

        Comment::insert([
            'user_id' => $user->id,
            'object_id' => $order->id,
            'object_type' => Comment::TYPE_OBJECT_ORDER,
            'scope' => Comment::TYPE_INTERNAL,
            'message' => $message,
            'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function __change_deposit(Request $request, Order $order, User $user){

//        $message = null;
//
//        Comment::insert([
//            'user_id' => $user->id,
//            'object_id' => $order->id,
//            'object_type' => Comment::TYPE_OBJECT_ORDER,
//            'scope' => Comment::TYPE_INTERNAL,
//            'message' => $message,
//            'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
//            'created_at' => date('Y-m-d H:i:s')
//        ]);
    }

    private function __domestic_shipping_china(Request $request, Order $order, User $user)
    {
        $domestic_shipping_fee = (double)$request->get('domestic_shipping_china');

        Order::where([
            'id' => $order->id
        ])->update([
            'domestic_shipping_fee' => $domestic_shipping_fee
        ]);

        Comment::insert([
            'user_id' => $user->id,
            'object_id' => $order->id,
            'object_type' => Comment::TYPE_OBJECT_ORDER,
            'scope' => Comment::TYPE_INTERNAL,
            'message' => sprintf('Cập nhật phí vận chuyển nội địa TQ %s ¥', $domestic_shipping_fee),
            'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        Comment::insert([
            'user_id' => $user->id,
            'object_id' => $order->id,
            'object_type' => Comment::TYPE_OBJECT_ORDER,
            'scope' => Comment::TYPE_EXTERNAL,
            'message' => sprintf('Cập nhật phí vận chuyển nội địa TQ %s ¥', $domestic_shipping_fee),
            'type_context' => Comment::TYPE_CONTEXT_ACTIVITY,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
