<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orders(Request $request){
        return view('customer/orders', [
            'page_title' => 'Danh sách đơn hàng',
        ]);
    }

    public function order(Request $request){
        $order_id = $request->route('id');

        $order = Order::find($order_id);

        if(!$order || !$order instanceof Order):
            return redirect('404');
        endif;

        if($order->user_id != Auth::user()->id):
            return redirect('403');
        endif;

        return view('customer/order_detail', [
            'page_title' => 'Chi tiết đơn hàng',
            'order' => $order
        ]);
    }
}
