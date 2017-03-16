<?php

namespace App\Http\Controllers;

use App\Cart;
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

    protected $action_error = [];

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

            $order->create_freight_bill($user->id, $freight_bill);

            $message = '';
            if($freight_bill_exists):
                $message = sprintf('Mã hóa đơn %s đã tồn tại ở 1 đơn hàng khác!', $freight_bill);
            endif;

            Comment::createComment($user, $order, sprintf('Thêm mã vận đơn %s', $freight_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

            $order_empty_freight_bill = $order->exist_freight_bill();
            if($order_empty_freight_bill
                && $order->status == Order::STATUS_BOUGHT){
                $order->changeStatus(Order::STATUS_SELLER_DELIVERY);
                Comment::createComment($user, $order, "Đơn hàng chuyển sang trạng thái người bán giao.", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                Comment::createComment($user, $order, "Chuyển trạng thái đơn sang người bán giao.", Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
            }

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

            Comment::createComment($user, $order, sprintf('Xóa mã vận đơn %s', $freight_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

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

            $order_original_bill_exists = $order->has_original_bill($original_bill);

            if($order_original_bill_exists):
                return response()->json(['success' => false, 'message' => sprintf('Mã hóa đơn gốc %s đã tồn tại!', $original_bill)]);
            endif;

            $original_bill_exists = OrderOriginalBill::where([
                'original_bill' => $original_bill
            ])->count();

            $order->create_original_bill($user->id, $original_bill);

            Comment::createComment($user, $order, sprintf('Thêm mã hóa đơn gốc %s', $original_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

            $message = '';

            if($original_bill_exists):
                $message = sprintf('Mã hóa đơn gốc %s đã tồn tại ở 1 đơn hàng khách!', $original_bill);
            endif;

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại hoặc liên hệ với kỹ thuật để được hỗ trợ!']);
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

            Comment::createComment($user, $order, sprintf('Xóa mã hóa đơn gốc %s', $original_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

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

            $result = $this->$action($request, $order, $user);
            if(!$result){
                return response()->json( ['success' => false, 'message' => implode('<br>', $this->action_error)] );
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'success']);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
        }

    }

    /**
     * @author vanhs
     * @desc Hanh dong mua don hang
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __bought_order(Request $request, Order $order, User $user){
        if($order->status != Order::STATUS_DEPOSITED){
            $this->action_error[] = sprintf('Đơn hiện đang ở trạng thái [%s], không thể chuyển sang đã mua!', Order::getStatusTitle($order->status));
        }

        if(empty($order->account_purchase_origin)){
            $this->action_error[] = 'Vui lòng chọn user mua hàng site gốc!';
        }

        $exists_original_bill = Order::find($order->id)->original_bill()->count();
        if(!$exists_original_bill){
            $this->action_error[] = 'Vui lòng nhập mã hóa đơn gốc!';
        }

        if(!empty($order->domestic_shipping_fee)){
            $this->action_error[] = 'Vui lòng nhập vào phí vận chuyển nội địa TQ!';
        }

        if(empty($order->receive_warehouse)){
            $this->action_error[] = 'Vui lòng chọn kho nhận hàng bên Trung Quốc!';
        }

        if(empty($order->destination_warehouse)){
            $this->action_error[] = 'Vui lòng chọn kho phân phối tại Việt Nam!';
        }

        if(count($this->action_error)){
            return false;
        }

        $customer = User::find($order->user_id);
        $order_amount = $order->amount(true);
        $deposit_percent_new = $order->deposit_percent;
        $deposit_amount_new = Cart::getDepositAmount($deposit_percent_new, $order_amount);
        $deposit_amount_old = UserTransaction::getDepositOrder($customer, $order);

        $order->paid_staff_id = $user->id;
        $order->status = Order::STATUS_BOUGHT;
        $order->deposit_amount = $deposit_amount_new;
        $order->bought_at = date('Y-m-d H:i:s');
        $order->save();

//        Order::where([
//            'id' => $order->id,
//            'user_id' => $user->id
//        ])->update([
//            'paid_staff_id' => Auth::user()->id,
//            'status' => Order::STATUS_BOUGHT,
//            'deposit_amount' => $deposit_amount_new,
//            'bought_at' => date('Y-m-d H:i:s'),
//        ]);

        Comment::createComment($user, $order, "Đơn hàng đã được mua thành công.", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        Comment::createComment($user, $order, "Đơn hàng đã được mua thành công.", Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        if($deposit_amount_new <> $deposit_amount_old){
            $temp = 'truy thu';
            $user_transaction_amount = 0 - abs($deposit_amount_old - $deposit_amount_new);
            if($deposit_amount_old > $deposit_amount_new){
                $user_transaction_amount = abs($deposit_amount_old - $deposit_amount_new);
                $temp = 'trả lại';
            }

            $message = sprintf("Hệ thống tiến hành %s số tiền %s để đảm bảo tỉ lệ đặt cọc %s phần trăm",
                $temp,
                abs($deposit_amount_old - $deposit_amount_new),
                $deposit_percent_new);

            Comment::createComment($user, $order, $message, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
            Comment::createComment($user, $order, $message, Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

            UserTransaction::createTransaction(
                UserTransaction::TRANSACTION_TYPE_DEPOSIT_ADJUSTMENT,
                $message,
                $user,
                $customer,
                $order,
                $user_transaction_amount
            );

//            $raw = DB::raw("account_balance-{$user_transaction_amount}");
//            if($user_transaction_amount > 0){
//                $raw = DB::raw("account_balance+{$user_transaction_amount}");
//            }
//            User::where(['id' => $user->id])->update([
//                'account_balance' => $raw
//            ]);
//
//            $user_after = User::find($user->id);
//
//            UserTransaction::insert([
//                'user_id' => $order->user_id,
//                'state' => UserTransaction::STATE_COMPLETED,
//                'transaction_code' => UserTransaction::generateTransactionCode(),
//                'transaction_type' => UserTransaction::TRANSACTION_TYPE_DEPOSIT_ADJUSTMENT,
//                'ending_balance' => $user_after->account_balance,
//                'created_by' => Auth::user()->id,
//                'object_id' => $order->id,
//                'object_type' => UserTransaction::OBJECT_TYPE_ORDER,
//                'amount' => $user_transaction_amount,
//                'transaction_detail' => json_encode($order),
//                'transaction_note' => $message,
//                'created_at' => date('Y-m-d H:i:s')
//            ]);
        }

        return true;
    }

    /**
     * @author vanhs
     * @desc Huy don hang
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __cancel_order(Request $request, Order $order, User $user){
        if($order->isAfterStatus(Order::STATUS_TRANSPORTING, true)){
            $this->action_error[] = 'Đơn hàng bắt đầu vận chuyển về Việt Nam. Không thể hủy đơn hàng!';
            return false;
        }

        $order->changeStatus(Order::STATUS_CANCELLED);

        $customer = User::find($order->user_id);

        $deposit_amount = UserTransaction::getDepositOrder($customer, $order);
        if($deposit_amount < 0){
            UserTransaction::createTransaction(
                UserTransaction::TRANSACTION_TYPE_REFUND,
                sprintf('Trả lại tiền đặt cọc đơn hàng %s', $order->code),
                $user,
                $user,
                $order,
                abs($deposit_amount)
            );
        }

        Comment::createComment($user, $order, "Hủy đơn hàng.", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        Comment::createComment($user, $order, "Hủy đơn hàng.", Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

    /**
     * @author vanhs
     * @desc Commment san pham
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __order_item_comment(Request $request, Order $order, User $user){
        $item_id = $request->get('item_id');
        $order_item = OrderItem::find($item_id);
        return Comment::createComment(
            $user,
            $order_item,
            $request->get('message'),
            Comment::TYPE_NONE,
            Comment::TYPE_CONTEXT_CHAT,
            $order
        );
    }

    /**
     * @author vanhs
     * @desc Them user mua hang site goc
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __account_purchase_origin(Request $request, Order $order, User $user){
        $message = null;

        $account_old = $order->account_purchase_origin;
        $account_new = $request->get('value');

        if(empty($account_old)){
            $message = sprintf('Chon user mua hàng site gốc %s', $account_new);
        }else{
            $message = sprintf('Thay đổi user mua hàng site gốc %s -> %s', $account_old, $account_new);
        }

        $order->account_purchase_origin = $account_new;
        $order->save();

        Comment::createComment($user, $order, $message, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

    /**
     * @author vanhs
     * @desc Thiet lap kho nhan hang
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __receive_warehouse(Request $request, Order $order, User $user){
        $message = null;

        $old = $order->receive_warehouse;
        $new = $request->get('value');

        if(empty($old)){
            $message = sprintf('Thiết lập kho nhận hàng %s', $new);
        }else{
            $message = sprintf('Thay đổi kho nhận hàng %s -> %s', $old, $new);
        }

        $order->receive_warehouse = $new;
        $order->save();

        Comment::createComment($user, $order, $message, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

    /**
     * @author vanhs
     * @desc Thiet lap kho phan phoi
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __destination_warehouse(Request $request, Order $order, User $user){
        $message = null;

        $old = $order->destination_warehouse;
        $new = $request->get('value');

        if(empty($old)){
            $message = sprintf('Thiết lập kho phân phối %s', $new);
        }else{
            $message = sprintf('Thay đổi kho phân phối %s -> %s', $old, $new);
        }

        $order->destination_warehouse = $new;
        $order->save();

        Comment::createComment($user, $order, $message, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

    /**
     * @author vanhs
     * @desc Doi ti le dat coc
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __change_deposit(Request $request, Order $order, User $user){

        $old_deposit_percent = $order->deposit_percent;
        $new_deposit_percent = (double)$request->get('deposit');

        if($new_deposit_percent > 100){
            $this->action_error[] = 'Tỉ lệ đặt cọc không hợp lệ!';
        }

        if(count($this->action_error)){
            return false;
        }

        $order->deposit_percent = $new_deposit_percent;
        $order->save();

        if($old_deposit_percent <> $new_deposit_percent){
            $message = sprintf("Thay đổi tỉ lệ đặt cọc đơn hàng từ %s thành %s", $old_deposit_percent, $new_deposit_percent);

            Comment::createComment($user, $order, $message, Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
            Comment::createComment($user, $order, $message, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        }


        return true;
    }

    /**
     * @author vanhs
     * @desc Phi van chuyen noi dia TQ
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __domestic_shipping_china(Request $request, Order $order, User $user)
    {
        $old_demestic_shipping_fee = $order->domestic_shipping_fee;
        $domestic_shipping_fee = $request->get('domestic_shipping_china');

        $order->domestic_shipping_fee = $domestic_shipping_fee;
        $order->save();

        Comment::createComment($user, $order, sprintf('Cập nhật phí vận chuyển nội địa TQ %s ¥', $domestic_shipping_fee), Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        Comment::createComment($user, $order, sprintf('Cập nhật phí vận chuyển nội địa TQ %s ¥', $domestic_shipping_fee), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }
}
