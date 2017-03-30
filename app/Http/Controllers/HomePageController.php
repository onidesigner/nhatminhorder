<?php

namespace App\Http\Controllers;

use App\SystemConfig;
use Doctrine\Common\Cache\Cache;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function __construct()
    {

    }

    public function homePage(){
        return view('home/index', [
            'enable_popup' => Cache::get(SystemConfig::CACHE_SYSTEM_CONFIG_KEY)['home_page_enable_popup'],
            'title_popup' => Cache::get(SystemConfig::CACHE_SYSTEM_CONFIG_KEY)['home_page_title_popup'],
            'content_popup' => Cache::get(SystemConfig::CACHE_SYSTEM_CONFIG_KEY)['home_page_content_popup']
        ]);
    }
}
