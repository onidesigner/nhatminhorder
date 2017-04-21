<?php

namespace App\Http\Controllers;

use App\Library\ServiceFee\ServiceFactoryMethod;
use App\Order;
use App\OrderFee;
use App\Package;
use App\Service;
use App\UserTransaction;
use Illuminate\Http\Request;

class HoSiVanController extends Controller
{
    function __construct()
    {

    }

    public function index(){
        OrderFee::truncate();

        $factoryMethodInstance = new ServiceFactoryMethod();

        $orders = Order::all();
        foreach($orders as $order){
            if(!$order instanceof Order){
                continue;
            }

            $order->save();

            $transactions = UserTransaction::where([
                'object_id' => $order->id,
                'object_type' => UserTransaction::OBJECT_TYPE_ORDER
            ])->get();

            if($transactions){
                foreach($transactions as $transaction){
                    if(!$transaction instanceof UserTransaction){
                        continue;
                    }
                    $transaction->save();
                }
            }

            $packages = Package::where([
                'order_id' => $order->id,
                'is_done' => 1
            ])->get();

            if($packages){
                foreach($packages as $package){
                    if(!$package instanceof Package){
                        continue;
                    }

                    $service = $factoryMethodInstance->makeService([
                        'service_code' => Service::TYPE_SHIPPING_CHINA_VIETNAM,
                        'weight' => $package->getWeightCalFee(),
                        'destination_warehouse' => $order->destination_warehouse,
                        'apply_time' => $order->deposited_at,
                    ]);
                    $money_charge = (float)$service->calculatorFee();
                    if($money_charge > 0){
                        $money_charge = 0 - abs($money_charge);
                    }

                    $data_fee_insert = [
                        [ 'name' => 'shipping_china_vietnam_fee', 'money' => (abs($money_charge) / $order->exchange_rate), 'update_money' => true ],
                        [ 'name' => 'shipping_china_vietnam_fee_vnd', 'money' => abs($money_charge), 'update_money' => true ],
                    ];
                    OrderFee::createFee($order, $data_fee_insert);
                }
            }
        }

        echo 'done<br/>';
    }
}
