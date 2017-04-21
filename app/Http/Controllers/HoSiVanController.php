<?php

namespace App\Http\Controllers;

use App\Library\ServiceFee\ServiceFactoryMethod;
use App\Order;
use App\OrderFee;
use App\Package;
use App\Service;
use App\User;
use App\UserTransaction;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoSiVanController extends Controller
{
    function __construct()
    {

    }

    public function index(Request $request){
        $action = $request->get('action');
        if($action == 'doi_soat_so_du'){

            exit;
        }

        if($action == 'thong_ke_tai_chinh'){
            $users = User::all();
            if($users){
                foreach($users as $user){
                    $account_balance_by_user_transaction = DB::table('user_transaction')
                        ->select(DB::raw('SUM(amount) as amount'))
                        ->where([
                            'user_id' => $user->id,
                            'state' => UserTransaction::STATE_COMPLETED,
                        ])
                        ->first()->amount;

                    echo '<h3>Khach hang: ' . $user->email . ' - ' . $user->code . '</h3>';
                    if($user->account_balance != $account_balance_by_user_transaction){
                        echo '<p style="color: red;">Giao dịch không trùng khớp</p>';
                    }
                    echo '<p>So du hien tai: ' . Util::formatNumber($user->account_balance) . 'đ</p>';
                    echo '<p>So du tinh theo lich su giao dich: ' . Util::formatNumber($account_balance_by_user_transaction) . '</p>';

                    echo '<hr>';
                }
            }
            exit;
        }

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
