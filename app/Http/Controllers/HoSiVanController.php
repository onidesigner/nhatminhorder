<?php

namespace App\Http\Controllers;

use App\Exchange;
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

    private function __cap_nhat_nguoi_duoc_phan_don_cho_nhung_don_hang_cu(Request $request){
        $orders = Order::where([
            ['crane_staff_id', '=', null],
            ['status', '!=', Order::STATUS_DEPOSITED]
        ])->get();

        if($orders){
            foreach($orders as $order){
                echo sprintf("<a href='%s' target='_blank'>don hang %s (%s)</a><br/>",
                    url('order/detail', $order->id),
                    $order->code,
                    Order::getStatusTitle($order->status));

                $crane_buying = User::find($order->paid_staff_id);
                if($crane_buying instanceof User){
                    $order->crane_staff_id = $order->paid_staff_id;
                    $order->crane_staff_at = $order->bought_at;
                    $order->save();

                    echo sprintf("nguoi mua %s - %s<hr>", $crane_buying->email, $crane_buying->code);
                }

            }
        }
    }

    private function __don_truy_thu_dat_coc(Request $request){
        //select * from `user_transaction` where transaction_type = 'DEPOSIT_ADJUSTMENT' and object_type = 'ORDER';
        $user_transaction = UserTransaction::where([
            'transaction_type' => UserTransaction::TRANSACTION_TYPE_DEPOSIT_ADJUSTMENT,
            'object_type' => UserTransaction::OBJECT_TYPE_ORDER
        ])->get();

        if($user_transaction){
            foreach($user_transaction as $item){
                if(!$item instanceof UserTransaction){
                    continue;
                }

                $order = Order::find($item->object_id);
                if(!$order instanceof Order){
                    continue;
                }

                echo sprintf("<p><a href='%s'>Don hang %s</a></p>", url('order/detail', $order->id), $order->code);
            }
        }
    }

    private function __xoa_du_lieu_khach_hang(Request $request){
        $user_id = $request->get('user_id');
        $user_email = $request->get('user_email');

        $user = null;

        if($user_id){
            $user = User::where([
                'id' => $user_id
            ])->first();
        } else if($user_email){
            $user = User::where([
                'email' => $user_email
            ])->first();
        }

        if(!$user instanceof User){
            echo '<p>khong ton tai user</p>';
            exit;
        }

        #region -- Xoa lich su giao dich --
        UserTransaction::where([
            'user_id' => $user->id
        ])->delete();
        User::where([
            'id' => $user->id
        ])->update([
            'account_balance' => 0
        ]);
        #endregion

        #region -- Xoa kien hang --
        DB::statement(sprintf('delete from package_service where package_id in (select id from packages where buyer_id = %s)', $user->id));
        DB::statement(sprintf('delete from packages where buyer_id = %s', $user->id));
        #endregion

        #region -- Xoa don hang --
        DB::statement(sprintf("delete from order_service where order_id in (select id from `order` where user_id = %s)", $user->id));
        DB::statement(sprintf("delete from order_original_bill where order_id in (select id from `order` where user_id = %s)", $user->id));
        DB::statement(sprintf("delete from order_item where order_id in (select id from `order` where user_id = %s)", $user->id));
        DB::statement(sprintf("delete from order_freight_bill where order_id in (select id from `order` where user_id = %s)", $user->id));
        DB::statement(sprintf("delete from order_fee where order_id in (select id from `order` where user_id = %s)", $user->id));
        DB::statement(sprintf("delete from `order` where user_id = %s", $user->id));
        #endregion

        echo sprintf('<p>Xoa du lieu thanh cong user: %s</p>', $user->email);
    }

    private function __linh_tinh(Request $request){




//        $order_fee = OrderFee::all();
//        foreach($order_fee as $order_fee_item){
//            if(!$order_fee_item instanceof OrderFee){
//                continue;
//            }
//            $order = Order::find($order_fee_item->order_id);
//            if(!$order instanceof Order){
//                $order_fee_item->delete();
//            }
//        }

//        $exchange_rate = Exchange::getExchange();
//
//        $order = Order::find(74);
//
//        $money_vnd = 62100;
//        $money = $money_vnd / $exchange_rate;
//
//        $data_fee_insert[] = [ 'name' => 'shipping_china_vietnam_fee', 'money' => $money, 'update_money' => false ];
//        $data_fee_insert[] = [ 'name' => 'shipping_china_vietnam_fee_vnd', 'money' => $money_vnd, 'update_money' => false ];
//
//        OrderFee::createFee($order, $data_fee_insert);
//
//        $order = Order::find(70);
//
//        $money_vnd = 111000;
//        $money = $money_vnd / $exchange_rate;
//
//        $data_fee_insert[] = [ 'name' => 'shipping_china_vietnam_fee', 'money' => $money, 'update_money' => false ];
//        $data_fee_insert[] = [ 'name' => 'shipping_china_vietnam_fee_vnd', 'money' => $money_vnd, 'update_money' => false ];
//
//        OrderFee::createFee($order, $data_fee_insert);
    }

    private function __tai_chinh_khong_khop(Request $request){
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

                $account_balance_by_user_transaction = doubleval($account_balance_by_user_transaction);
                $user_account_balance = doubleval($user->account_balance);

                echo '<h3>Khach hang: ' . $user->email . ' - ' . $user->code . '</h3>';
                if($user_account_balance <> $account_balance_by_user_transaction){
                    echo '<p style="color: red;">Giao dịch không trùng khớp</p>';
                }
                echo '<p>So du hien tai: ' . Util::formatNumber($user_account_balance) . 'đ</p>';
                echo '<p>So du tinh theo lich su giao dich: ' . Util::formatNumber($account_balance_by_user_transaction) . 'đ</p>';

                echo '<hr>';
            }
        }
        exit;
    }

    private function __tinh_toan_lai_phi_tren_don(Request $request){
        die('huhu');
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

//            $packages = Package::where([
//                'order_id' => $order->id,
//                'is_done' => 1
//            ])->get();
//
//            if($packages){
//                foreach($packages as $package){
//                    if(!$package instanceof Package){
//                        continue;
//                    }
//
//                    $service = $factoryMethodInstance->makeService([
//                        'service_code' => Service::TYPE_SHIPPING_CHINA_VIETNAM,
//                        'weight' => $package->getWeightCalFee(),
//                        'destination_warehouse' => $order->destination_warehouse,
//                        'apply_time' => $order->deposited_at,
//                    ]);
//                    $money_charge = (float)$service->calculatorFee();
//                    if($money_charge > 0){
//                        $money_charge = 0 - abs($money_charge);
//                    }
//
//                    $data_fee_insert = [
//                        [ 'name' => 'shipping_china_vietnam_fee', 'money' => (abs($money_charge) / $order->exchange_rate), 'update_money' => true ],
//                        [ 'name' => 'shipping_china_vietnam_fee_vnd', 'money' => abs($money_charge), 'update_money' => true ],
//                    ];
//                    OrderFee::createFee($order, $data_fee_insert);
//
//                }
//            }
        }
    }

    public function index(Request $request){
        $action = '__' . $request->get('action');

        if (!method_exists($this, $action)) {
            die('Not support action!');
        }

        $result = $this->$action($request);

        die('done');
    }
}
