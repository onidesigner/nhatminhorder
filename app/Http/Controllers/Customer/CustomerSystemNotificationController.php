<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 20/06/2017
 * Time: 14:08
 */

namespace App\Http\Controllers\Customer;


use App\CustomerNotification;
use App\Http\Controllers\Controller;
use App\SystemNotification;
use App\User;
use App\UserFollowObject;
use App\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerSystemNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request ){

        $page = $request->get('page');

        $per_page = 50;

        if(!$page || $page == 1){
            $page = 0;
        }else{
            $page = $page - 1;
        }
       
        $current_user = User::find(Auth::user()->id);
        $user_follows = UserFollowObject::where([
            'follower_id' => $current_user->id,
            'status' => UserFollowObject::STATUS_ACTIVE
        ])->get();
        $list_notification = [];
        if(count($user_follows) > 0){
            $list_order = [];
            $list_complaint = [];
            foreach ($user_follows as $item_followers){
                /** @var UserFollowObject $item_followers */
                 if($item_followers->object_type == 'ORDER'){
                        $list_order[] = $item_followers->object_id;
                 }
            }
            $list_notification =
                SystemNotification::where('follower_id',"=", $current_user->id)
                ->whereIn('object_id',$list_order)
                ->paginate($per_page);

        }

        return view('customer/customer_system_notification', [
            'page_title' => 'notifications',
            'data' => $list_notification,
            'per_page' => $per_page,
            'page' => $page
        ]);
    }

    /**
     * đếm số thông báo hiển thị cho người dùng
     * @return \Illuminate\Http\JsonResponse
     */
    public function countNotificationFollowerUser(){
        $current_user = User::find(Auth::user()->id);
        $user_follows = UserFollowObject::where([
            'follower_id' => $current_user->id,
            'status' => UserFollowObject::STATUS_ACTIVE
        ])->get();
        $list_notification_count = 0;
        $list_notification = [];
        if(count($user_follows) > 0){
            $list_order = [];
            $list_complaint = [];
            foreach ($user_follows as $item_followers){
                /** @var UserFollowObject $item_followers */
                if($item_followers->object_type == 'ORDER'){
                    $list_order[] = $item_followers->object_id;
                }
            }
            $list_notification_count =
                SystemNotification::where('follower_id',"=", $current_user->id)
                    ->whereIn('notify_status',
                        [SystemNotification::TYPE_VIEW,SystemNotification::TYPE_READ])
                    #->whereIn('object_id',$list_order)
                    ->count();

            $list_notification =
                SystemNotification::where('follower_id',"=", $current_user->id)
                    ->whereIn('notify_status',
                        [SystemNotification::TYPE_VIEW,SystemNotification::TYPE_READ])
                   # ->whereIn('object_id',$list_order)
                   ->orderby('id','desc')
                    ->get();

        }

        return response()->json([
            'notification_count' => $list_notification_count,
            'list_notification' => $list_notification,
            'type' => 'success'
        ]);
    }

    public function loadContentNotify(){
        $current_user = User::find(Auth::user()->id);
        $user_follows = UserFollowObject::where([
            'follower_id' => $current_user->id,
            'status' => UserFollowObject::STATUS_ACTIVE
        ])->get();
        $output = '';
        if(count($user_follows) > 0){
            $list_order = [];
            $list_complaint = [];
            foreach ($user_follows as $item_followers){
                /** @var UserFollowObject $item_followers */
                if($item_followers->object_type == 'ORDER'){
                    $list_order[] = $item_followers->object_id;
                }
            }

            $list_notification =
                SystemNotification::where('follower_id',"=", $current_user->id)
//                    ->whereIn('notify_status',
//                        [SystemNotification::TYPE_VIEW,SystemNotification::TYPE_READ])
                 #   ->whereIn('object_id',$list_order)
                    ->orderby('id','desc')
                    ->get();

           if(count($list_notification) > 0){
                foreach ($list_notification as $item_notification){
                    $user_id = $item_notification->follower_id;
                    if(in_array($item_notification->notify_status,[SystemNotification::TYPE_READ,SystemNotification::TYPE_VIEW])){
                        $status = 'Mới';
                    }else{
                        $status = 'Đã Xem';
                    }

                    $user = User::find($user_id);
                    // gawns link vao comment
                    $output .= '
                    
                          <li class="_change_status" data-follower-id="'.$item_notification->id.'">          
                                        <a  target="_blank" href="'.$this->buidLink($item_notification).'" >
                                            <span class="badge badge-danger pull-right">'.
                                                                $status
                                            .'</span>
                                            <div class="message">
                                                <div class="content">
                                                    <div class="title">'.$item_notification->title.'</div>
                                                    <div class="description">'.$item_notification->notification_content.'</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                    
                    
                    ';

                }
           }else{
               $output .= '<li><a href="#" class="text-bold text-italic">Bạn không có thông báo mới</a></li>';
           }
        }
        return response()->json([
            'notification' => $output,
            'type' => 'success'
        ]);
    }

    /**
     * hàm xây dựng trả ra đường link khi truyền vào một
     * @param SystemNotification $system_notification
     * @return string
     */
    private function buidLink($system_notification){
        $object_type = $system_notification->object_type;
        $follower_id = $system_notification->follower_id;
        $type_notify = $system_notification->type;

        $link = '';
        $user = Auth::user();
        if($object_type == SystemNotification::TYPE_ORDER && $type_notify == SystemNotification::TYPE_READ){

            if($user->section == User::SECTION_CRANE){
                $link = "/order/".$system_notification->object_id;
            }else{
                $link = "/don-hang/".$system_notification->object_id;
            }

        }else{
            if($user->section == User::SECTION_CRANE){
                $link = "/transactions";
            }else{
                $link = "/giao-dich";
            }

        }

        return $link;
    }

    /**
     * ddanhs dau da doc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function changeStatusFollow( Request $request ){
        $follower_id = $request->get('follower_id');

        $follower = SystemNotification::find($follower_id);

        if($follower instanceof SystemNotification){

            if($follower->type == SystemNotification::TYPE_VIEW){
                $follower->notify_status = SystemNotification::STATUS_VIEWED;
            }
            if($follower->type == SystemNotification::TYPE_READ){
                $follower->notify_status = SystemNotification::STATUS_READ;
            }

            $follower->save();
            return response()->json([
                'type' => 'success'
            ]);
        }
        return response()->json([
            'type' => 'error'
        ]);
    }
}