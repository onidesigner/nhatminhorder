<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 13/04/2017
 * Time: 21:40
 */

namespace App\Http\Controllers\Customer;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CustomerNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index(){
        $data_notification = DB::table('customer_notification')
                                ->where('user_id', '=', Auth::user()->id)
                                ->orderBy('id', 'desc')->paginate(20);
        if(count($data_notification) < 1){
         $data_notification = [];
        }
        return view('customer/notification',[
            'data' => $data_notification,
            'page_title' => 'notification'
        ]);
    }



}