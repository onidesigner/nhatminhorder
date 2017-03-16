<?php

namespace App\Http\Controllers\Customer;
use App\Comment;
use App\Exchange;
use App\Http\Controllers\Controller;
use App\Order;
use App\User;
use App\UserTransaction;
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

        $deposit_amount = UserTransaction::getDepositOrder($user, $order);
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
