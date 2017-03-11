<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\OrderFreightBill;
use App\OrderOriginalBill;
use App\Permission;
use App\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Order;

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
        $order_id = $request->get('order_id');
        $freight_bill = $request->get('freight_bill');

        $order = Order::find($order_id);

        if(!$order):
            return response()->json(['success' => false, 'message' => 'Order not found!']);
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

        if($freight_bill_exists):
            return response()->json(['success' => true, 'message' => sprintf('Mã hóa đơn %s đã tồn tại ở 1 đơn hàng khác!', $freight_bill)]);
        endif;

        return response()->json(['success' => true, 'message' => '']);
    }

    /**
     * @author vanhs
     * @desc Xoa ma van don
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFreightBill(Request $request){
        $order_id = $request->get('order_id');
        $freight_bill = $request->get('freight_bill');

        $order = Order::find($order_id);

        if(!$order):
            return response()->json(['success' => false, 'message' => 'Order not found!']);
        endif;

        $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_REMOVE_FREIGHT_BILL);
        if(!$can_execute):
            return response()->json(['success' => false, 'message' => 'Not permission!']);
        endif;

        OrderFreightBill::where([
            'order_id' => $order_id,
            'freight_bill' => $freight_bill
        ])->delete();

        return response()->json(['success' => true, 'message' => 'delete success']);
    }

    /**
     * @author vanhs
     * @desc Them ma hoa don site goc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertOriginalBill(Request $request){
        $order_id = $request->get('order_id');
        $original_bill = $request->get('original_bill');

        $order = Order::find($order_id);

        if(!$order):
            return response()->json(['success' => false, 'message' => 'Order not found!']);
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

        if($original_bill_exists):
            return response()->json(['success' => true, 'message' => sprintf('Mã hóa đơn gốc %s đã tồn tại ở 1 đơn hàng khách!', $original_bill)]);
        endif;

        return response()->json(['success' => true, 'message' => '']);
    }

    /**
     * @author vanhs
     * @desc Xoa ma hoa don site goc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeOriginalBill(Request $request){
        $order_id = $request->get('order_id');
        $original_bill = $request->get('original_bill');

        $order = Order::find($order_id);

        if(!$order):
            return response()->json(['success' => false, 'message' => 'Order not found!']);
        endif;

        $can_execute = Permission::isAllow(Permission::PERMISSION_ORDER_REMOVE_ORIGINAL_BILL);
        if(!$can_execute):
            return response()->json(['success' => false, 'message' => 'Not permission!']);
        endif;

        OrderOriginalBill::where([
            'order_id' => $order_id,
            'original_bill' => $original_bill
        ])->delete();

        return response()->json([ 'success' => true, 'message' => 'delete success' ]);
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

        $order_items = $order->item;
        $transactions = UserTransaction::where([
            'object_id' => $order->code,
            'object_type' => UserTransaction::OBJECT_TYPE_ORDER,
            'state' => UserTransaction::STATE_COMPLETED
        ])->orderBy('created_at', 'desc')
            ->get();

        return view('order_detail', [
            'order_id' => $order_id,
            'freight_bill' => $freight_bill,
            'original_bill' => $original_bill,
            'order' => $order,
            'order_items' => $order_items,
            'transactions' => $transactions,
            'page_title' => 'Chi tiết đơn hàng',
        ]);
    }
}
