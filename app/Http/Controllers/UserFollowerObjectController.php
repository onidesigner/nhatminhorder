<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 28/06/2017
 * Time: 09:30
 */

namespace App\Http\Controllers;


use App\UserFollowObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFollowerObjectController extends Controller
{
    public function index(){

        $user_id = Auth::user()->id;

        $data = UserFollowObject::where([
            'follower_id' => $user_id
        ])->orderby('id','desc')
            ->get();

        return view('user_follow_object',[
            'data' => $data,
            'page_title' => 'Danh sach theo dõi đơn'
        ]);
    }
    
    public function unfollow(Request $request){
        $order_id = $request->get('order_id');
        $current_user = Auth::user()->id;

        $update = UserFollowObject::where([
            'object_id' => $order_id,
            'follower_id' => $current_user
        ])->update([
            'status' => UserFollowObject::STATUS_INACTIVE
        ]);
        if($update){

            return response()->json([
                'type' => 'true'
            ]);
        }else{
            return response()->json([
                'type' => 'false'
            ]);
        }
    }

}