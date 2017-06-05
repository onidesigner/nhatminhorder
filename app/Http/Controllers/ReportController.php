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
        foreach ($buying_fees as $item_buying){
            /** @var $item_buying OrderFee */
            if($this->checkStatusOrder($item_buying)){

            }
        }





        return view('report',[
            'data' => $data_return,
            'total_package' => $package_weight,
            'page_title' => 'Báo cáo sản lượng hàng tháng',
        ]);

    }

    /**
     * kiểm tra trạng thái của đơn hàng
     * @param $order_id
     * @return bool
     */
    private function checkStatusOrder($order_id){
        $order = Order::findOneByIdOrCode($order_id);
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