<?php

namespace App\Http\Controllers\Customer;
use App\Comment;
use App\Exchange;
use App\Http\Controllers\Controller;
use App\Order;
use App\Package;
use App\Service;
use App\User;
use App\UserTransaction;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @desc Danh sach don hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orders(Request $request){
        $exchange_rage = Exchange::getExchange();
        $per_page = 50;
        $orders = Order::select('*')
            ->where([
                'user_id' => Auth::user()->id
            ])
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return view('customer/orders', [
            'page_title' => 'Danh sách đơn hàng',
            'exchange_rage' => $exchange_rage,
            'orders' => $orders,
        ]);
    }

    /**
     * @author vanhs
     * @desc Chi tiet don hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function order(Request $request){
        $order_id = $request->route('id');

        $order = Order::find($order_id);

        if(!$order || !$order instanceof Order):
            return redirect('404');
        endif;

        if($order->user_id != Auth::user()->id):
            return redirect('403');
        endif;

        $can_cancel_order = $order->isBeforeStatus(Order::STATUS_BOUGHT);

        return view('customer/order_detail', [
            'page_title' => 'Chi tiết đơn hàng',
            'can_cancel_order' => $can_cancel_order,
            'order_id' => $order_id,
            'order' => $order
        ]);
    }

    /**
     * @author vanhs
     * @desc Xu ly toan bo hanh dong trang trang chi tiet don hang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request){
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

            if($order->user_id <> $user->id){
                return response()->json(['success' => false, 'message' => 'Action reject!']);
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

//    private function __customer_delivery(Request $request, Order $order, User $user){
//        if($order->status != Order::STATUS_WAITING_DELIVERY){
//            $this->action_error[] = 'Trạng thái không hợp lệ!';
//        }
//
//        if(count($this->action_error)){
//            return false;
//        }
//
//        $order->changeStatus(Order::STATUS_CUSTOMER_DELIVERY);
//
//        $total_package_weight_payment = 0;
//        $packages_payment = $order->package()->where([ 'status' => '' ])->get();
//        if($packages_payment){
//            foreach($packages_payment as $package_payment){
//                if(!$package_payment instanceof Package){
//                    continue;
//                }
//                $total_package_weight_payment += (float)$package_payment->weight;
//            }
//        }
//
//        $customer_payment_order = UserTransaction::getPaymentOrder($user, $order);
//        $total_order_payment = 0;
//
//        $total_amount_vnd = $order->amountWithItems(true);
//        $shipping_china_vietnam = $order->getShippingChinaVietnam($total_package_weight_payment);
//        $total_order_payment += $total_amount_vnd;
//        $total_order_payment += $order->getBuyingFee($total_amount_vnd);
//        if($order->existService(Service::TYPE_CHECKING)){
//            $total_order_payment += $order->getCheckingFee();
//        }
//        if($order->existService(Service::TYPE_WOOD_CRATING)){
//            $total_order_payment += $order->getWoodCrating($shipping_china_vietnam, 10, 10);
//        }
//
//        Comment::createComment($user, $order, "Chuyển trạng thái đơn hàng sang yêu cầu giao", Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
//        Comment::createComment($user, $order, "Chuyển trạng thái đơn hàng sang yêu cầu giao", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
//
//        if($customer_payment_order <> $total_order_payment){
//            $temp = 'truy thu';
//            $user_transaction_amount = 0 - abs($customer_payment_order - $total_order_payment);
//            if($customer_payment_order > $total_order_payment){
//                $user_transaction_amount = abs($customer_payment_order - $total_order_payment);
//                $temp = 'trả lại';
//            }
//
//            $message = sprintf("Hệ thống tiến hành %s số tiền %s",
//                $temp,
//                Util::formatNumber(abs($customer_payment_order - $total_order_payment)));
//
//            Comment::createComment($user, $order, $message, Comment::TYPE_INTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
//            Comment::createComment($user, $order, $message, Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
//
//            UserTransaction::createTransaction(
//                UserTransaction::TRANSACTION_TYPE_DEPOSIT_ADJUSTMENT,
//                $message,
//                $user,
//                $user,
//                $order,
//                $user_transaction_amount
//            );
//        }
//
//        return true;
//    }

    private function __received_order(Request $request, Order $order, User $user){
        if($order->status != Order::STATUS_DELIVERING){
            $this->action_error[] = 'Trạng thái không hợp lệ!';
        }

        if(count($this->action_error)){
            return false;
        }

        $order->changeStatus(Order::STATUS_RECEIVED);
        Comment::createComment($user, $order, "Chuyển trạng thái đơn sang đã nhận hàng", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);
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
        if($order->isAfterStatus(Order::STATUS_BOUGHT, true)){
            $this->action_error[] = 'Không thể hủy đơn hàng. Liên hệ với NhatMinh247 để được hỗ trợ';
        }

        if(count($this->action_error)){
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
                $customer,
                $order,
                abs($deposit_amount)
            );
        }

        Comment::createComment($user, $order, "Hủy đơn hàng.", Comment::TYPE_EXTERNAL, Comment::TYPE_CONTEXT_ACTIVITY);

        return true;
    }

}
