<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 23/05/2017
 * Time: 15:20
 */

namespace App\Http\Controllers;


class PackageWeightController extends Controller
{
        public function index(){
            return view('input_weight_package',[
                'page_title' => 'Nhập cân nặng hàng hóa',
            ]);
        }
}