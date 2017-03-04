<?php

namespace App\Http\Controllers;

use App\Library\ServiceFee\AbstractService;
use App\Library\ServiceFee\Buying;
use App\Library\ServiceFee\ServiceFactoryMethod;
use App\Service;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $factoryMethodInstance = new ServiceFactoryMethod();

        //============phi mua hang===========
        $service = $factoryMethodInstance->makeService([
            'service_code' => Service::TYPE_BUYING,
            'total_amount' => 6000,
            'apply_time' => '2017-09-01 00:00:00'
        ]);
        /** @var AbstractService $service */
        var_dump('phi mua hang');
        var_dump($service->calculatorFee());

        //============phi kiem hang===========
        $order = Order::find(516223);
        $total_quantity_items_normal = $order->getItemNormalQuantity();
        $total_quantity_items_assess = $order->getItemAssessQuantity();

        $service = $factoryMethodInstance->makeService([
            'service_code' => Service::TYPE_CHECKING,
            'total_quantity_items_normal' => $total_quantity_items_normal,
            'total_quantity_items_assess' => $total_quantity_items_assess,
            'apply_time' => '2017-09-01 00:00:00'
        ]);
        /** @var AbstractService $service */
        var_dump('phi kiem hang');
        var_dump($service->calculatorFee());

        //============phi van chuyen TQ-VN===========
        $order = Order::find(516223);
        $destination_warehouse = $order->destination_warehouse;
        $destination_warehouse = 'BT-SG';

        //-- Can nang truyen vao phai chuyen sang kg
        $service = $factoryMethodInstance->makeService([
            'service_code' => Service::TYPE_SHIPPING_CHINA_VIETNAM,
            'weight' => 10,
            'destination_warehouse' => $destination_warehouse,
            'apply_time' => '2017-09-01 00:00:00'
        ]);
        /** @var AbstractService $service */
        var_dump('phi van chuyen TQ - VN');
        var_dump($service->calculatorFee());

        //============phi dong go===========
        $service = $factoryMethodInstance->makeService([
            'service_code' => Service::TYPE_WOOD_CRATING,
            'fee_shipping_china_vietnam' => 50000,//phi van chuyen quoc te
            'weight_manual' => 10,//can nang tinh
            'weight_equivalent' => 50,//can nang quy doi
            'calculator_by' => 'MANUAL',//MANUAL hoac EQUIVALENT
            'apply_time' => '2017-09-01 00:00:00'
        ]);
        /** @var AbstractService $service */
        var_dump('phi dong go');
        var_dump($service->calculatorFee());

//        die();

        return view('home', [
            'page_title' => 'Home'
        ]);
    }
}
