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

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ham goi den danh sach
     */
    public function index(){

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



}