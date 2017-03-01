<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateUser(Request $request){
        $user_id = $request->get('id');
        $password = trim($request->get('password'));
        $name = $request->get('name');
        $user = User::find($user_id);

        $rules['name'] = 'required';

        if($password):
            $rules['password'] = 'required|min:6';
//            $user->password = Hash::make($password);
            $user->password = bcrypt($password);
        endif;

        $this->validate($request, $rules);

        if(!empty($name)):
            $user->name = $name;
        endif;

        $user->section = $request->get('section');
        $user->status = $request->get('status');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();
        return redirect("sua-nhan-vien/{$user_id}");
    }

    public function getUser(Request $request){
        $user_id = $request->route('id');
        $user = User::find($user_id);

        if(!$user):
            return redirect('404');
        endif;

        return view('user_detail', [
            'page_title' => "Sua thong tin nhan vien [" . $user['email'] . "]",
            'section_list' => User::$section_list,
            'status_list' => User::$status_list,
            'user_id' => $user_id,
            'user' => $user
        ]);
    }

    /**
     * @author vanhs
     * @desc Ham lay thong tin hien thi danh sach nhan vien
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUsers(){
        $users = User::orderby('id', 'desc')->paginate(20);
        $json_data = json_decode($users->toJson(), true);

        return view('users', [
            'page_title' => 'Danh sach nhan vien',
            'users' => $json_data
        ]);
    }

}
