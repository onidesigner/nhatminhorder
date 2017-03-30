<?php

namespace App\Http\Controllers;

use App\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomePageController extends Controller
{
    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homePage(){
        $data_return = [
            'enable_popup' => false,
            'title_popup' => null,
            'content_popup' => null,
        ];
        foreach($data_return as $k => $v){
            $data_return[$k] = !empty(Cache::get(SystemConfig::CACHE_SYSTEM_CONFIG_KEY)['home_page_' . $k])
                ? Cache::get(SystemConfig::CACHE_SYSTEM_CONFIG_KEY)['home_page_' . $k] : $v;
        }
        return view('home/index', $data_return);
    }
}
