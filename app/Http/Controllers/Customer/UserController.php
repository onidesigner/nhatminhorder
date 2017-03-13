<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function detail(Request $request){
        return view('customer/user_detail', [
            'page_title' => 'Thông tin cá nhân',
        ]);
    }
}
