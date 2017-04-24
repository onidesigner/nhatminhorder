<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function users(Request $request){
        $where = [];
        if(!empty($request->get('user_code'))){
            $where[] = [ 'code', '=', $request->get('user_code') ];
        }

        $users = User::where([
            'section' => User::SECTION_CUSTOMER
        ])
            ->where($where);

        $total_users = $users->count();

        $users = $users->orderBy('id', 'desc')
            ->get();



        if($users){
            foreach($users as $user){
                if(!$user instanceof User){
                    continue;
                }

            }
        }

        return view ('statistic_users', [
            'page_title' => 'Thống kê tài chính khách hàng',
            'users' => $users,
            'total_users' => $total_users,
        ]);
    }
}
