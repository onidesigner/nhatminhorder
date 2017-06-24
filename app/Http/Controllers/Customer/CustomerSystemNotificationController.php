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
     * load nội dung và con số đếm hiển thị
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadContentNotify( Request $request ){
        $currentPage = $request->get('currentPage',1);
        $pageSize = $request->get('pageSize',10);

        $currentPage = $currentPage - 1;

        $current_user = User::find(Auth::user()->id);


        #count notify

        $list_notification_count =
            SystemNotification::where('follower_id',"=", $current_user->id)
                ->whereIn('notify_status',
                    [SystemNotification::TYPE_VIEW,SystemNotification::TYPE_READ])
                ->count();

        # endcount notify


        $user_follows = UserFollowObject::where([
            'follower_id' => $current_user->id,
            'status' => UserFollowObject::STATUS_ACTIVE
        ])->get();
        $output = '';
        if(count($user_follows) > 0){

            $list_notification =
                SystemNotification::where('follower_id',"=", $current_user->id)
                ->orderby('id','desc')
//                ->offset($currentPage*$pageSize)
//                ->limit($pageSize)
                ->get();

            $id = [];
           if(count($list_notification) > 0){
                foreach ($list_notification as $item_notification){
                    $id[] = $item_notification->id;
                    $color = '';
                    if(in_array($item_notification->notify_status,[SystemNotification::TYPE_READ,SystemNotification::TYPE_VIEW])){
                        $status = 'Mới';
                        $color = 'badge badge-danger';
                    }else{
                        $status = '<i class="fa fa-check" aria-hidden="true" style="color: #0e90d2"></i>';
                    }

                    $output .= '
                    
                          <li class="_change_status" data-follower-id="'.$item_notification->id.'">          
                                        <a  target="_blank" href="'.$this->buidLink($item_notification).'" >
                                            <span class=" '. $color.' pull-right">'.
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
           }
        }
        return response()->json([
            'notification' => $output,
            'count_notify' => $list_notification_count,
            'type' => 'success',

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


    /**
     * check ham
     */
    public function convertNotification(){

        $oldNotifycation = CustomerNotification::whereIn('is_view',['VIEW','READ'])->get();

        foreach($oldNotifycation as $item_old){

            $new_notify = new SystemNotification();
            $new_notify->object_id = $item_old->order_id;
            $new_notify->follower_id = $item_old->user_id;
            $new_notify->title = $item_old->title;
            $new_notify->notification_content = $item_old->notification_content;
            $new_notify->object_type = $item_old->type;
            $new_notify->type = $item_old->is_view;
            $new_notify->notify_status = $item_old->is_view;

            if($new_notify->save()){
                var_dump( $item_old->id." success");
            }else{
                var_dump( $item_old->id." fail");
            }


        }
    }
}