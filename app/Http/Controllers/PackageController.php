<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexs()
    {
        return view('package', [
            'page_title' => 'Quản lý kiện hàng'
        ]);
    }
}
