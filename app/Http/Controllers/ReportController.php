<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 03/06/2017
 * Time: 09:41
 */

namespace App\Http\Controllers;


use App\Order;
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

        $data_return = [];
        $package_weight_user = [];
        $package_weight = 0;
        if(sizeof($data_package) > 0){

            foreach ($data_package as $item_package){
                if($item_package->weight == 0 && $item_package->convert_weight == 0){
                        continue;
                }

                $package_weight_user[] = $item_package->buyer_id;

                if($item_package->weight > $item_package->convert_weight){
                    $package_weight += $item_package->weight;
                }else{
                    $package_weight += $item_package->convert_weight;
                }
            }
        }
        $package_user = [];
        foreach ($package_weight_user as $item_package_user){

            $package = Package::where('buyer_id',$item_package_user)->get();
            $package_weight_belong_user = self::packageWeight($package);

            $package_user[$item_package_user] = $package_weight_user;

        }


        return view('report',[
            'data' => $data_return,
            'total_package' => $package_weight,
            'page_title' => 'Báo cáo sản lượng hàng tháng',
            'package_user' => $package_weight_belong_user
        ]);

    }

    /**
     * ham tinh can nawng cuar kien khi truyen vafo mot mang
     * kien hang
     * @param $package
     * @return int
     */
    private static function packageWeight($package = array()){
        $package_weight = 0;
        foreach ($package as $item_package){
            if($item_package->weight == 0 && $item_package->convert_weight == 0){
                continue;
            }

            $package_weight_user[] = $item_package->buyer_id;

            if($item_package->weight > $item_package->convert_weight){
                $package_weight += $item_package->weight;
            }else{
                $package_weight += $item_package->convert_weight;
            }
        }

        return $package_weight;
    }



}