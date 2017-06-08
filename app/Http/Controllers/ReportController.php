<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 03/06/2017
 * Time: 09:41
 */

namespace App\Http\Controllers;


use App\Order;
use App\OrderFee;
use App\Package;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ham goi den danh sach
     */
    public function indexs(){

        // đẩy dữ liệu từ trên server xuống dưới
        $data_package = Package::where('is_deleted',0)

            ->whereIn('status',[Package::STATUS_RECEIVED,Package::STATUS_DELIVERING])->get();

        $data_package_paginate = Package::where('is_deleted',0)

            ->whereIn('status',[Package::STATUS_RECEIVED,Package::STATUS_DELIVERING])->get();

        $data_return = [];

        $package_weight = 0;
        if(sizeof($data_package) > 0){

            foreach ($data_package as $item_package){
                /** @var Package $item_package */
                if($item_package->weight == 0 && $item_package->convert_weight == 0){
                        continue;
                }
                $package_weight += $item_package->getWeightCalFee();

            }
            /**
             * laays gas trij hienj thi
             */
            foreach ($data_package_paginate as $item_package_panigate){
                if($item_package_panigate->weight == 0 && $item_package_panigate->convert_weight == 0){
                    continue;
                }
                $data_return[] = $item_package_panigate;
            }
        }


        // thống kê tổng giá trị tiền vận chuyển nội địa chung quốc

        # tống tiền phí dịch vụ
        $buying_fees = OrderFee::where('name','BUYING_FEE_VND')->get();

        $total_buying_fee = 0;
        foreach ($buying_fees as $item_buying){
            /** @var $item_buying OrderFee */
            if(self::checkStatusOrder($item_buying->order_id) == true){
                $total_buying_fee += $item_buying->money;
            }
        }
        #end tổng phí dịch vụ

        #region phí vận chuyển nội địa chung quốc

        $domictic_shipping_fee = OrderFee::where('name','DOMESTIC_SHIPPING_FEE_VND')->get();
        $total_domictic_shipping_fee = 0;
        foreach ($domictic_shipping_fee as $item_domictic){
            /** @var $item_domictic OrderFee */
            $total_domictic_shipping_fee += $item_domictic->money;
        }

        #endregion Phí vận chuyển nội địa trung quốc




        return view('report',[
            'data' => $data_return,
            'total_package' => $package_weight,
            'total_buying_fee' => $total_buying_fee,
            'total_domictic_shipping_fee' => $total_domictic_shipping_fee,
            'page_title' => 'Báo cáo sản lượng hàng tháng',
        ]);

    }

    /**
     * kiểm tra trạng thái của đơn hàng
     * @param $order_id
     * @return bool
     */
    private static function checkStatusOrder($order_id){
        $order = Order::where('id',$order_id)->first();
        if($order instanceof Order){
            if($order->status == Order::STATUS_CANCELLED){
                return false;
            }else{
                return true;
            }
        }

        return false;
    }

    /**
     * hàm thống kê dựa trên điêu kiện kho và thời gian
     */
    public function index(Request $request){
        $time_from = $request->get('date1');
        $time_to = $request->get('date2'); //
        $warehouse = $request->get('warehouse');// kho hien tai
        $warehouse_status = $request->get('warehouse_status'); // trang thái xuất hay nhập kh
        
        $packages = Package::select('*');

        $packages = $packages->orderBy('id', 'desc');
        $packages = $packages->where('is_deleted',0);

        if($warehouse){
            $packages = $packages->where('current_warehouse',$warehouse);
        }
        // thoi gian chinh la thoi gian nhap kho
        if($warehouse_status){
            $packages = $packages->where('warehouse_status',$warehouse_status);
        }
        if($time_from){
            if(!$warehouse_status || $warehouse_status == 'IN' ){
                $packages = $packages->where('warehouse_status_in_at','>=',$time_from." 00:00:00");
            }elseif($warehouse_status == 'OUT'){
                $packages = $packages->where('warehouse_status_out_at','>=',$time_from." 00:00:00");
            }
        }
        if($time_to){
            if(!$warehouse_status || $warehouse_status == 'IN'){
                $packages = $packages->where('warehouse_status_in_at','<',$time_to." 23:59:59");
            }elseif($warehouse_status == 'OUT'){
                $packages = $packages->where('warehouse_status_out_at','<',$time_to." 23:59:59");
            }
            
        }
        
        $packages = $packages->get();
        $total_package = $packages->count(); // tong so luong kien hang

        // tinh tong trong luong hang hoa van chuyen
        $package_weight = 0;
        // lay cac ma don tuong ung
        $order_id = [];
        foreach ($packages as $item_package){
            /** @var $item_package Package */
            $package_weight += $item_package->getWeightCalFee();

            $order_id[] = $item_package->order_id;
        }

        $list_order_id = array_unique($order_id);


        /**
         * phí của đơn hàng, được tính ở đây
         */
        $order_fee = OrderFee::whereIn('order_id',$list_order_id)->get();

        // tiền hàng
        $customer_payment_order = 0;
        // tiền ship nội địa
        $domestic_shipping_fee = 0;
        // thu phi 1% dich vu
        $buying_fee = 0;

        /**
         * lấy giá trị
         */
        foreach ($order_fee as $item_domistic_shipping){

            /** @var $item_domistic_shipping OrderFee */
            // tien hang
            if($item_domistic_shipping->name == OrderFee::AMOUNT_VND){
                
                $customer_payment_order += $item_domistic_shipping->money;
            }
            // tien phi ship noi dia
            if($item_domistic_shipping->name == OrderFee::DOMESTIC_SHIPPING_FEE_VND){
                $domestic_shipping_fee += $item_domistic_shipping->money;
            }
            // phi mua hang
            if($item_domistic_shipping->name == OrderFee::BUYING_FEE_VND){
                $buying_fee += $item_domistic_shipping->money;
            }

        }
        return view('report',[

            'total_package' => $total_package,
            'total_buying_fee' => $buying_fee,
            'total_domictic_shipping_fee' => $domestic_shipping_fee,
            'page_title' => 'Báo cáo sản lượng hàng tháng',
        ]);











    }

}