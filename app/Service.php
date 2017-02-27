<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Service extends Model
{

    protected $table = 'services';

//    public static function getServiceFrontend(){
//        $data = [];
//        $services = DB::table('services')->get();
//        if($services):
//            foreach($services as $service):
//                $code = $service->code;
//
//                if(in_array($code, ['SHIPPING_CHINA_VIETNAM', 'FRAGILE', 'HIGH_VALUE', 'BUYING'])):
//                    continue;
//                endif;
//
//                $data[] = [
//                    'title' => $service->title,
//                    'code' => $service->code
//                ];
//            endforeach;
//        endif;
//        return $data;
//    }
}
