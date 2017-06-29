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
use Illuminate\Support\Facades\DB;


class CustomerSystemNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index( Request $request ){

        $page = $request->get('page');
        $status = $request->get('status');

        if($status){
            if($status == 'READ'){
                $where = [SystemNotification::STATUS_READ,SystemNotification::STATUS_VIEWED];
            }else{
                $where = [SystemNotification::TYPE_READ,SystemNotification::TYPE_VIEW];
            }
        }else{
            $where = [SystemNotification::STATUS_READ,SystemNotification::STATUS_VIEWED,
                SystemNotification::TYPE_READ,SystemNotification::TYPE_VIEW];
        }

        $per_page = 20;

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
        $list_notification_all = 0;
        if(count($user_follows) > 0){

            $list_notification_all =
                SystemNotification::where('follower_id',"=", $current_user->id)
                    ->where('is_deleted',0)
                    ->whereIn('notify_status',$where)
                    ->orderby('id','desc')
                    ->paginate($per_page);
        }

        return view('customer/customer_system_notification', [
            'page_title' => 'notifications',
            'data' => $list_notification_all,
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

        $current_page = $request->get('currentPage',1);
        $current_page = $current_page -1;

        $pageSize = 10;
        $current_user = Auth::user();



        #count notify

        $list_notification_count =
            SystemNotification::where('follower_id',"=", $current_user->id)
                ->where('is_deleted',0)
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

            /**
             * đếm giữ liệu trả về
             */
            $list_notification_all =
                SystemNotification::where('follower_id',"=", $current_user->id)
                    ->where('is_deleted',0)
                    ->count();


            /**
             * content trả về
             */
            $list_notification =  SystemNotification::where('follower_id',"=", $current_user->id)
                ->orderby('id','desc')
                ->offset($current_page*$pageSize)
                ->limit($pageSize)
                ->get();


           if(count($list_notification) > 0){

                foreach ($list_notification as $item_notification){
                    $color = '';
                    if(in_array($item_notification->notify_status,[SystemNotification::TYPE_READ,SystemNotification::TYPE_VIEW])){
                        $status = 'Mới';
                        $color = 'badge badge-danger';
                    }else{
                        $status = '<i class="fa fa-check" aria-hidden="true" style="color: #0e90d2"></i>';
                    }

                    $output .= '
                    
                          <li class="_change_status" data-follower-id="'.$item_notification->id.'">          
                                        <a  target="_blank" href="'.self::buidLink($item_notification).'" >
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
            'page_size' => $pageSize,
            'count_notify' => $list_notification_count,
            'notification_display' => $list_notification_all,
            'type' => 'success',

        ]);
    }

    /**
     * hàm xây dựng trả ra đường link khi truyền vào một
     * @param SystemNotification $system_notification
     * @return string
     */
    public static function buidLink($system_notification){
        $object_type = $system_notification->object_type;
        $follower_id = $system_notification->follower_id;
        $type_notify = $system_notification->type;

        $link = '';
        $user = Auth::user();
        if($object_type == SystemNotification::TYPE_ORDER || $object_type == 'CHAT'){

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
     * đánh dấu tất cả đã đọc
     */
    public function markreadall(){

        $current_user = Auth::user();
        $list_notification = SystemNotification::where('follower_id',"=", $current_user->id)
                ->where('is_deleted',0)
                ->whereIn('notify_status',
                [SystemNotification::TYPE_VIEW,SystemNotification::TYPE_READ])
                ->orderby('id','desc')
                ->get();

        if(count($list_notification) == 0){
            return response()->json([
                'type' => 'success'
            ]);
        }
        $arr_view = [];
        $arr_read = [];
        foreach ($list_notification as $item){
                if($item->type == SystemNotification::TYPE_READ){
                    $arr_read[] = $item->id;
                }else{
                    $arr_view[] = $item->id;
                }
        }

        if(count($arr_read) > 0){
            SystemNotification::whereIn('id',$arr_read)
                ->update(['notify_status' => SystemNotification::STATUS_READ]);
        }
        if(count($arr_view) >0){
            SystemNotification::whereIn('id',$arr_view)
                ->update(['notify_status' => SystemNotification::STATUS_VIEWED]);
        }

        return response()->json([
            'type' => 'success'
        ]);

    }

    /**
     * check ham
     */
        public function convertNotification(){

            $value =  SystemNotification::where('is_deleted',0)
                ->whereIn('notify_status',['READ','VIEW'])
                ->orderby('id','desc')
                ->get();
            foreach ($value as $item){

                    $user_follow = new UserFollowObject();
                    $user_follow->object_id = $item->object_id;
                    $user_follow->object_type = 'ORDER';
                    $user_follow->follower_id = $item->follower_id;
                    $user_follow->status = 'ACTIVE';
                    $user_follow->save();
            }



            $users = DB::table('user_follow_object')
                ->select(DB::raw(' min(id)'))
                ->where('id', '>', 1)
                ->groupBy('object_id','follower_id')
                ->having(DB::raw('count(*)'), '>', 1)
                ->get();



            $array = [];
            foreach ($users as $items){
                $items =  get_object_vars($items);
                $array[] = $items['min(id)'];
            }


            foreach ($array as $arr_item){
                $user_follow =  UserFollowObject::where('id',$arr_item)->first();
                $user_follow_remove = UserFollowObject::where([
                    'object_id' => $user_follow->object_id,
                    'follower_id' => $user_follow->follower_id
                ])->get();

               foreach ($user_follow_remove as $item_remove){

                   if($item_remove->id != $arr_item ){
                       var_dump($item_remove->id);
                       /** @var $item_remove UserFollowObject $x */
                       $x = $item_remove->delete();
                       var_dump($x);
                   }
               }
            }







    }
}