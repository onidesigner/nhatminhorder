<?php

namespace App\Http\Controllers;

use App\Comment;
use App\CustomerNotification;
use App\Order;
use App\SystemNotification;
use App\UserFollowObject;
use App\UserFollowOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\View;
use Mockery\CountValidator\Exception;

class CommentController extends Controller
{
    protected $action_error = [];

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function action(Request $request)
    {
        try{
            DB::beginTransaction();

            $user = User::find(Auth::user()->id);
            $action = '__' . $request->get('action');

            if(!$user || !$user instanceof User){
                return response()->json(['success' => false, 'message' => 'User not found!']);
            }

            if(empty($request->get('object_id'))
                && empty($request->get('object_type'))
                && empty($request->get('scope_view'))){
                return response()->json(['success' => false, 'message' => 'Params is missing!']);
            }

            if (!method_exists($this, $action)) {
                return response()->json(['success' => false, 'message' => 'Not support action!']);
            }

            $result = $this->$action($request, $user);
            if(!$result){
                return response()->json( ['success' => false, 'message' => implode('<br>', $this->action_error)] );
            }

            DB::commit();

            $view = View::make($request->get('response'), [
                'object_id' => $request->get('object_id'),
                'object_type' => $request->get('object_type'),
                'scope_view' => $request->get('scope_view'),
            ]);
            $html = $view->render();

            return response()->json([
                'success' => true,
                'message' => 'success',
                'html' => $html,
                'anchor' => $request->get('anchor'),
            ]);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    /**
     * @author vanhs
     * @desc Them comment
     * @param Request $request
     * @return mixed
     */
    private function __comment(Request $request, $user){
        try{
            if(empty($request->get('message'))){
                $this->action_error[] = 'Nội dung không để trống!';
            }

            if(count($this->action_error)){
                return false;
            }
            $order_id = $request->get('object_id');


            $object_id = $request->get('object_id');

            $comment = new Comment();
            $comment->user_id = $user->id; // id của người comment trên đơn
            $comment->object_id = $request->get('object_id');
            $comment->object_type = $request->get('object_type');
            $comment->scope = $request->get('scope');
            $comment->message = $request->get('message');
            $comment->type_context = Comment::TYPE_CONTEXT_CHAT;
            $comment->is_public_profile = $request->get('is_public_profile');
            $comment->save();
            $order = Order::findOneByIdOrCode($order_id);
            $tile = 'Trao đổi trên đơn'.' '.$order->code;
            $notification_content = $user->name." trao đổi trên đơn hàng ".$order->code;
            // điều kiện nào để update vào bảng

            // lấy ra giá trị của đối tượng xem có nhận được ko
            $user_follow = UserFollowObject::where([
                'object_id' => $object_id,
                'follower_id' => $user->id,
                'status' => UserFollowObject::STATUS_ACTIVE
            ])->get();
            // nguoi theo doi la nguoi cat tren don luon
            if(count($user_follow) == 0){
                $user_follow_object = new UserFollowObject();
                $user_follow_object->createUserFollow($order,$user);
            }

            $list_user_follow = UserFollowObject::where([
                'object_id' => $object_id,
                'status' => UserFollowObject::STATUS_ACTIVE
            ])->get();

            $list_follower_id = [];
            foreach($list_user_follow as $item_user){
                /** @var UserFollowObject $item_user */
                $list_follower_id[] = $item_user->follower_id;

            }

            if(count($list_follower_id) > 0){
                $key = array_search($user->id, $list_follower_id);
                unset($list_follower_id[$key]);
            }
            $list_users = User::whereIn('id',$list_follower_id)->get();


            // kiểm tra xem có cho lưu hay ko


            foreach ($list_users as $item_user){
                $notify = new SystemNotification();
                $notify->createSystemNotificationChat($order,$item_user,$tile,$notification_content);
            }
                return true;
        }catch ( Exception $e){
             return $e->getMessage();
        }


    }
}
