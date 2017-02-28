<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\User;

class CommentController extends Controller
{
    public $user_id = null;
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function getComment(Request $request){
        $data_where = $request->all();

        unset($data_where['_token']);

        $current_user_id = Auth::user()->id;

        //kiem tra xem user nay co quyen xem doi tuong nay hay khong?
        switch ($data_where['object_type']):
            case Comment::TYPE_OBJECT_ORDER:
                break;
        endswitch;

        $comment = new Comment();
        $return_data = $comment->getComments($data_where);
        $return_data = $return_data->toArray();

        foreach($return_data as $key => $return_data_item):
            $name = null;
            if($return_data_item['user_id']):
                    $name = User::find($return_data_item['user_id'])->name;
            endif;
            $return_data[$key]['name'] = $name;
        endforeach;

        return Response::json(array('success' => true, 'data' => $return_data));
    }

    public function addNewComment(Request $request){
        $data_insert = $request->all();
        $current_user_id = Auth::user()->id;

        unset($data_insert['_token']);
        $data_insert['type_context'] = Comment::TYPE_CONTEXT_CHAT;
        $data_insert['user_id'] = $current_user_id;
        $data_insert['created_at'] = date('Y-m-d H:i:s');

        $comment = new Comment();
        $comment->addNewComment($data_insert);

        $data_insert['name'] = Auth::user()->name;

        return Response::json(array('success' => true, 'data' => [ $data_insert ]));
    }
}
