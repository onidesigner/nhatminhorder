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

    /**
     * @author vanhs
     * @desc Them comment moi
     * @param Request $request
     * @return mixed
     */
    public function addNewComment(Request $request){
        $data_insert = $request->all();
        $current_user_id = Auth::user()->id;

        unset($data_insert['_token']);
        $data_insert['type_context'] = Comment::TYPE_CONTEXT_CHAT;
        $data_insert['user_id'] = $current_user_id;
        $data_insert['created_at'] = date('Y-m-d H:i:s');

        Comment::insert($data_insert);

        $data_insert['name'] = Auth::user()->name;

        return Response::json(array('success' => true, 'data' => [ $data_insert ]));
    }
}
