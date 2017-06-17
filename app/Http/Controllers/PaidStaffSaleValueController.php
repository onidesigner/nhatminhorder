<?php

namespace App\Http\Controllers;

use App\Order;
use App\Permission;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaidStaffSaleValueController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @author vanhs
     * @desc Thong ke doanh so mua hang
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(Request $request){
        $can_view = Permission::isAllow(Permission::PERMISSION_PAID_STAFF_SALE_VALUE);
        if(!$can_view){
            return redirect('403');
        }

        $start_month = null;
        $end_month = null;

        if($request->get('month')){
            $temp = explode('_', $request->get('month'));
            $m = $temp[0];
            $y = $temp[1];
            $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);

            $start_month = sprintf("%s-%s-01", $y, $m);
            $end_month = sprintf("%s-%s-%s", $y, $m, $d);
        }

//        $start_month = $request->get('start_month');
//        $end_month = $request->get('end_month');

        if(empty($start_month)) $start_month = sprintf("%s-%s-01", date('Y'), date('m'));
        if(empty($end_month)) $end_month = sprintf("%s-%s-%s", date('Y'), date('m'), date('t'));

        $start_month .= ' 00:00:00';
        $end_month .= ' 23:59:59';

        $crane_buying_ids = [];
        $crane_buying_list = [];

        /**
         * Neu la chua, nguoi quan ly thi co the xem duoc doanh so cua tat ca moi nguoi
         * Neu la nv mua hang thi chi xem duoc cua ca nhan ma thoi
         */
        $permission_buying_mange = Permission::isAllow(Permission::PERMISSION_MANAGER_PAID_STAFF_SALE_VALUE);
        $current_user = User::find(Auth::user()->id);
        if($current_user->isGod() || $permission_buying_mange){
            $crane_buying_list = User::getListCraneBuying();
        }else{
            $crane_buying_list[] = User::find(Auth::user()->id);
        }


        if($crane_buying_list){
            foreach($crane_buying_list as $crane_buying_list_item){
                $crane_buying_ids[] = $crane_buying_list_item->id;
            }
        }

        $crane_buying_ids_string = implode(',', $crane_buying_ids);

        $sql = "
        
        select * from `order` 
        where 
        ((`received_at` >= '".$start_month."' and `received_at` <= '".$end_month."') 
        or (`bought_at` >= '".$start_month."' and `bought_at` <= '".$end_month."'))
        and paid_staff_id in (".$crane_buying_ids_string.") 
        and `status` not in ('CANCELLED')

        ";

        $orders = DB::select($sql);
        $orders_with_crane_buying = [];
        if($orders){
            foreach($orders as $order){

                $order->amount_customer = $order->amount + $order->domestic_shipping_fee;
                $order->amount_customer_vnd = $order->amount_customer * $order->exchange_rate;

                $order->amount_original_vnd = $order->amount_original * $order->exchange_rate;

                $order->amount_bargain = $order->amount_customer - $order->amount_original;

                //neu khong dien tong gia thuc mua thi don nay coi nhu khong mac ca duoc gi
                $order->amount_original = doubleval($order->amount_original);
//                var_dump($order->amount_original);
                if($order->amount_original <= 0){
                    $order->amount_bargain = 0;
                }
                $order->amount_bargain_vnd = $order->amount_bargain * $order->exchange_rate;

                $order->is_done = $order->status == Order::STATUS_RECEIVED;

                $order->amount_bargain_done = 0;
                $order->amount_bargain_done_vnd = 0;
                $order->amount_bargain_not_done = 0;
                $order->amount_bargain_not_done_vnd = 0;

                if($order->is_done){
                    $order->amount_bargain_done = $order->amount_bargain;
                    $order->amount_bargain_done_vnd = $order->amount_bargain_vnd;
                }else{
                    $order->amount_bargain_not_done = $order->amount_bargain;
                    $order->amount_bargain_not_done_vnd = $order->amount_bargain_vnd;
                }

                $orders_with_crane_buying[$order->paid_staff_id][] = $order;

            }
        }

        return view('paid_staff_sale_value', [
           'page_title' => 'Doanh số, lương mua hàng',
            'crane_buying_list' => $crane_buying_list,
//            'orders' => $orders,
            'orders_with_crane_buying' => $orders_with_crane_buying
        ]);
    }
}
