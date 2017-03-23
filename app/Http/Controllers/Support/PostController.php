<?php

namespace App\Http\Controllers\Support;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index(Request $request){
        $post_id = $request->route('id');

        $post = Post::find($post_id);
        if(!$post || !$post instanceof Post){
            return redirect('404');
        }

        $author = User::find($post->create_user_id);
        if(!$author || !$author instanceof User){
            return redirect('404');
        }

        return view('support/post', [
            'page_title' => 'Bài viết',
            'post' => $post,
            'author' => $author,
        ]);
    }
}
