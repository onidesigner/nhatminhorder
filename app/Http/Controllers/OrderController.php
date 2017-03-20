<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Comment;
use App\Exchange;
use App\Location;
use App\OrderFreightBill;
use App\OrderItem;
use App\OrderOriginalBill;
use App\OrderService;
use App\Permission;
use App\User;
use App\UserAddress;
use App\UserOriginalSite;
use App\UserTransaction;
use App\Util;
use App\WareHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{

    protected $action_error = [];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @author vanhs
     * @desc Danh sach don hang
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orders(){
        $params = Input::all();
        $per_page = 50;

        $orders = Order::select('*');
        $orders = $orders->orderBy('id', 'desc');
        if(!empty($params['status'])){
            $orders = $orders->whereIn('status', explode(',', $params['status']));
        }
        $total_orders = $orders->count();
        $orders = $orders->paginate($per_page);

        $status_list = [];
        foreach(Order::$statusTitle as $key => $val){
            $selected = false;
            if(!empty($params['status'])){
                $selected = in_array($key, explode(',', $params['status']));
            }
            $status_list[] = [
                'key' => $key,
                'val' => $val,
                'selected' => $selected
            ];
        }

        return view('orders', [
            'page_title' => ' Quản lý đơn hàng',
            'orders' => $orders,
            'total_orders' => $total_orders,
            'status_list' => $status_list,
            'params' => $params,
        ]);
    }

    /**
     * @author vanhs
     * @desc Chi tiet don hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function order(Request $request){

        $can_view = Permission::isAllow(Permission::PERMISSION_ORDER_VIEW);
        if(!$can_view):
            return redirect('403');
        endif;

        $order_id = $request->route('id');
        $order = Order::findOneByIdOrCode($order_id);
        if(!$order):
            return redirect('404');
        endif;

        $customer = User::find($order->user_id);
        if(!$customer || !$customer instanceof User){
            return redirect('404');
        }

        $user_address = UserAddress::find($order->user_address_id);
        if($user_address && $user_address instanceof UserAddress){
            $district = Location::find($user_address->district_id);
            if($district && $district instanceof Location){
                $user_address->district_label = $district->label;
            }
            $province = Location::find($user_address->province_id);
            if($province && $province instanceof Location){
                $user_address->province_label = $province->label;
            }
        }

        $order_item_comments_data = [];
        $order_item_comments = Order::findByOrderItemComments($order->id);
        if($order_item_comments){
            foreach($order_item_comments as $order_item_comment){
                $order_item_comment->user = User::find($order_item_comment->user_id);
                $order_item_comments_data[$order_item_comment->object_id][] = $order_item_comment;
            }
        }

        $permission = [
            'can_change_order_bought' => $order->status == Order::STATUS_DEPOSITED,
            'can_change_order_item_quantity' => $order->isBeforeStatus(Order::STATUS_BOUGHT),
            'can_change_order_item_price' => $order->isBeforeStatus(Order::STATUS_BOUGHT),
            'can_change_order_account_purchase_origin' => $order->isBeforeStatus(Order::STATUS_BOUGHT),
            'can_change_order_domestic_shipping_fee' => $order->isBeforeStatus(Order::STATUS_BOUGHT),
            'can_change_order_deposit_percent' => $order->isBeforeStatus(Order::STATUS_BOUGHT),
        ];

        $fee = $order->fee($customer);

        $order_fee = [];
        foreach(Order::$fee_field_order_detail as $key => $label){
            $value = $key;
            if(isset($fee[$key])){
                $value = Util::formatNumber($fee[$key]);
            }
            $order_fee[] = [
                'label' => $label,
                'value' => $value,
            ];
        }

        return view('order_detail', [
            'order_id' => $order_id,
            'freight_bill' => $order->freight_bill()->where([ 'is_deleted' => 0 ])->get(),
            'original_bill' => $order->original_bill()->where([ 'is_deleted' => 0 ])->get(),
            'warehouse_distribution' => WareHouse::findByType(WareHouse::TYPE_DISTRIBUTION),
            'warehouse_receive' => WareHouse::findByType(WareHouse::TYPE_RECEIVE),
            'user_address' => $user_address,
            'order' => $order,
            'order_service' => $order->service,
            'order_item_comments' => $order_item_comments_data,
            'user_origin_site' => UserOriginalSite::all(),
            'order_items' => $order->item,
            'order_fee' => $order_fee,
            'customer' => $customer,
            'transactions' => Order::findByTransactions($order->id),
            'page_title' => 'Chi tiết đơn hàng',
            'permission' => $permission
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



            if($order->isEndingStatus()){
                return response()->json(['success' => false, 'message' => 'Đơn hàng hiện đã ở trạng thái cuối, không thể thay đổi thông tin!']);
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

    private function __insert_freight_bill(Request $request, Order $order, User $user){

        $freight_bill = $request->get('freight_bill');

        $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_ADD_FREIGHT_BILL);
        if(!$can_execute):
            $this->action_error[] = 'Not permission!';
        endif;

        if(empty($freight_bill)):
            $this->action_error[] = 'Mã vận đơn không để trống!';
        endif;

        $order_freight_bill_exists = $order->has_freight_bill($freight_bill);

        if($order_freight_bill_exists):
            $this->action_error[] = sprintf('Mã hóa đơn %s đã tồn tại', $freight_bill);
        endif;

        if(count($this->action_error)){
            return false;
        }

//        $freight_bill_exists = OrderFreightBill::where([
//            'freight_bill' => $freight_bill,
//            'is_deleted' => 0,
//        ])->count();

        $order->create_freight_bill($user->id, $freight_bill);

//        $message = '';
//        if($freight_bill_exists):
//            $message = sprintf('Mã hóa đơn %s đã tồn tại ở 1 đơn hàng khác!', $freight_bill);
//        endif;

        Comment::createComment($user, $order, sprintf('Thêm mã vận đơn %s', $freight_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        $order_empty_freight_bill = $order->exist_freight_bill();
        if($order_empty_freight_bill
            && $order->status == Order::STATUS_BOUGHT){
            $order->changeStatus(Order::STATUS_SELLER_DELIVERY);
            Comment::createComment($user, $order, "Đơn hàng chuyển sang trạng thái người bán giao.", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
            Comment::createComment($user, $order, "Chuyển trạng thái đơn sang người bán giao.", Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        }

        return true;
    }

    private function __remove_freight_bill(Request $request, Order $order, User $user){

        $freight_bill = $request->get('freight_bill_delete');

        $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_REMOVE_FREIGHT_BILL);
        if(!$can_execute):
            $this->action_error[] = 'Not permission!';
        endif;

        if(count($this->action_error)){
            return false;
        }

        OrderFreightBill::where([
            'order_id' => $order->id,
            'freight_bill' => $freight_bill
        ])->update([
            'is_deleted' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        Comment::createComment($user, $order, sprintf('Xóa mã vận đơn %s', $freight_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

    private function __insert_original_bill(Request $request, Order $order, User $user){
        $original_bill = $request->get('original_bill');

        $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_ADD_ORIGINAL_BILL);
        if(!$can_execute):
            $this->action_error[] = 'Not permission!';
        endif;

        if(empty($original_bill)):
            $this->action_error[] = 'Mã hóa đơn gốc không để trống!';
        endif;

        $order_original_bill_exists = $order->has_original_bill($original_bill);

        if($order_original_bill_exists):
            $this->action_error[] = sprintf('Mã hóa đơn gốc %s đã tồn tại!', $original_bill);
        endif;

        if(count($this->action_error)){
            return false;
        }

//        $original_bill_exists = OrderOriginalBill::where([
//            'original_bill' => $original_bill,
//            'is_delete' => 0,
//        ])->count();

        $order->create_original_bill($user->id, $original_bill);

        Comment::createComment($user, $order, sprintf('Thêm mã hóa đơn gốc %s', $original_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

//        $message = '';
//        if($original_bill_exists):
//            $message = sprintf('Mã hóa đơn gốc %s đã tồn tại ở 1 đơn hàng khách!', $original_bill);
//        endif;

        return true;
    }

    private function __remove_original_bill(Request $request, Order $order, User $user){

        $original_bill = $request->get('original_bill_delete');

        $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_REMOVE_ORIGINAL_BILL);
        if(!$can_execute):
            $this->action_error[] = 'Not permission!';
        endif;

        if(count($this->action_error)){
            return false;
        }

        OrderOriginalBill::where([
            'order_id' => $order->id,
            'original_bill' => $original_bill
        ])->update([
            'updated_at' => date('Y-m-d H:i:s'),
            'is_deleted' => 1
        ]);

        Comment::createComment($user, $order, sprintf('Xóa mã hóa đơn gốc %s', $original_bill), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

    /**
     * @author vanhs
     * @desc Them/bo dich vu tren don
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __change_order_service(Request $request, Order $order, User $user){

        $action_check = $request->get('action_check');
        $service_code = $request->get('service_code');
        $service_name = $request->get('service_name');

        $exists_service = $order->existService($service_code);
        switch ($action_check){
            case 'check':
                if(!$exists_service){
                    OrderService::addService($order->id, $service_code);

                    Comment::createComment($user, $order, sprintf("Chọn dịch vụ %s", $service_name), Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                    Comment::createComment($user, $order, sprintf("Chọn dịch vụ %s", $service_name), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                }
                break;
            case 'uncheck':
                if($exists_service){
                    OrderService::removeService($order->id, $service_code);

                    Comment::createComment($user, $order, sprintf("Bỏ chọn dịch vụ %", $service_name), Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                    Comment::createComment($user, $order, sprintf("Bỏ chọn dịch vụ %", $service_name), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
                }
                break;
        }
        return true;
    }

    /**
     * @author vanhs
     * @desc Thay doi so luong san pham
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __change_order_item_quantity(Request $request, Order $order, User $user){
        $item_id = $request->get('item_id');
        $order_item = OrderItem::find($item_id);

        if(!$order->isBeforeStatus(Order::STATUS_BOUGHT)){
            $this->action_error[] = 'Không được phép sửa số lượng sản phẩm ở trạng thái ' . Order::getStatusTitle($order->status);
        }

        if(!$order_item || !$order_item instanceof OrderItem){
            $this->action_error[] = 'Sản phẩm #' . $item_id . ' không tồn tại!';
        }

        if(count($this->action_error)){
            return false;
        }

        $old_order_quantity = $order_item->order_quantity;
        $new_order_quantity = (int)$request->get('value');
        $order_item->order_quantity = $new_order_quantity;
        $order_item->save();

        $order->total_order_quantity = $order->total_order_quantity();
        $order->amount = $order->amountWithItems();
        $order->save();

        if($old_order_quantity <> $new_order_quantity){
            Comment::createComment(
                $user,
                $order_item,
                sprintf("Sửa số lượng sản phẩm từ %s¥ thành %s¥", $old_order_quantity, $new_order_quantity),
                Comment::TYPE_NONE,
                Comment::TYPE_CONTEXT_ACTIVITY,
                $order
            );
        }


        return true;
    }

    /**
     * @author vanhs
     * @desc Thay doi gia san pham
     * @param Request $request
     * @param Order $order
     * @param User $user
     * @return bool
     */
    private function __change_order_item_price(Request $request, Order $order, User $user){
        $item_id = $request->get('item_id');
        $order_item = OrderItem::find($item_id);

        if(!$order->isBeforeStatus(Order::STATUS_BOUGHT)){
            $this->action_error[] = 'Không được phép sửa số lượng sản phẩm ở trạng thái ' . Order::getStatusTitle($order->status);
        }

        if(!$order_item || !$order_item instanceof OrderItem){
            $this->action_error[] = 'Sản phẩm #' . $item_id . ' không tồn tại!';
        }

        if(count($this->action_error)){
            return false;
        }

        $old_order_item_price = 0;
        $new_order_item_price = (double)$request->get('value');
        $order_item->price = $new_order_item_price;
        $order_item->price_promotion = $new_order_item_price;
        $order_item->save();

        $order->amount = $order->amountWithItems();
        $order->save();

        if($old_order_item_price <> $new_order_item_price){
            Comment::createComment(
                $user,
                $order_item,
                sprintf("Sửa giá sản phẩm từ %s thành %s", $old_order_item_price, $new_order_item_price),
                Comment::TYPE_NONE,
                Comment::TYPE_CONTEXT_ACTIVITY,
                $order
            );
        }

        return true;
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
        $order_amount = $order->amountWithItems(true);
        $deposit_percent_new = $order->deposit_percent;
        $deposit_amount_new = Cart::getDepositAmount($deposit_percent_new, $order_amount);
        $deposit_amount_old = UserTransaction::getDepositOrder($customer, $order);
        $deposit_amount_old = abs($deposit_amount_old);

        $order->changeStatus(Order::STATUS_BOUGHT, false);
        $order->paid_staff_id = $user->id;
        $order->deposit_amount = $deposit_amount_new;
        $order->save();

        Comment::createComment($user, $order, "Đơn hàng đã được mua thành công.", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        Comment::createComment($user, $order, "Đơn hàng đã được mua thành công.", Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        $user_transaction_amount = 0 - abs($deposit_amount_old - $deposit_amount_new);

        if($user_transaction_amount <> 0){
            $text = 'truy thu';

            if($deposit_amount_old > $deposit_amount_new){
                $user_transaction_amount = abs($deposit_amount_old - $deposit_amount_new);
                $text = 'trả lại';
            }

            $message = sprintf("Hệ thống tiến hành %s số tiền %s để đảm bảo tỉ lệ đặt cọc %s phần trăm",
                $text,
                Util::formatNumber(abs($deposit_amount_old - $deposit_amount_new)),
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
        if(!$order->isBeforeStatus(Order::STATUS_BOUGHT)){
            $this->action_error[] = sprintf('Không thể thay đổi acc mua hàng khi đơn ở trạng thái %s', Order::getStatusTitle($order->status));
        }

        if(count($this->action_error)){
            return false;
        }

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

        if(!$order->isBeforeStatus(Order::STATUS_BOUGHT)){
            $this->action_error[] = 'Không được phép sửa tỉ lệ đặt cọc đơn ở trạng thái này!';
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
        if(!$order->isBeforeStatus(Order::STATUS_BOUGHT)){
            $this->action_error[] = sprintf('Không thể thay đổi phí vận chuyển nội địa TQ khi đơn ở trạng thái %s', Order::getStatusTitle($order->status));
        }

        if(count($this->action_error)){
            return false;
        }

        $old_demestic_shipping_fee = $order->domestic_shipping_fee;
        $domestic_shipping_fee = $request->get('domestic_shipping_china');

        $order->domestic_shipping_fee = $domestic_shipping_fee;
        $order->save();

        Comment::createComment($user, $order, sprintf('Cập nhật phí vận chuyển nội địa TQ %s ¥', $domestic_shipping_fee), Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
        Comment::createComment($user, $order, sprintf('Cập nhật phí vận chuyển nội địa TQ %s ¥', $domestic_shipping_fee), Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }
}
