<?php

namespace App\Http\Controllers;

use App\Exchange;
use Illuminate\Http\Request;
use App\Order;

class OrderController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');

        $this->order = new Order();
    }

    public function getOrders(){
        $exchange_rage = Exchange::getExchange();
        $per_page = 50;
        $orders = Order::select('*')
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return view('orders', [
            'page_title' => 'Quan ly don hang',
            'orders' => $orders,
            'exchange_rage' => $exchange_rage
        ]);
    }

    public function getOrder(Request $request){
        $order_id = $request->get('id');

        return view('order_detail', [
            'order_id' => $order_id,
            'page_title' => 'Chi tiet don hang',
        ]);
    }
}
