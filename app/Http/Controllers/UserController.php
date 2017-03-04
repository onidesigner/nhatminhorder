<?php

namespace App\Http\Controllers;

use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\User;
use App\UserTransaction;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addUserPhone(Request $request){
        $user_phone = $request->get('user_phone');

        if(!$user_phone):
            return Response::json(['success' => false, 'message' => 'So dien thoai khong hop le!']);
        endif;

        $user = User::find(Auth::user()->id);

        if(!$user):
            return Response::json(['success' => false, 'message' => 'User khong hop le!']);
        endif;

        $can_addnew = false;
        if(Auth::user()->id == $user->id):
            $can_addnew = true;
        endif;

        if(!$can_addnew):
            return Response::json(['success' => false, 'message' => 'Khong co quyen thuc hien hanh dong nay!']);
        endif;

        if($user->checkMaxMobile()):
            return Response::json(['success' => false, 'message' => sprintf('Ban chi duoc them toi da %s so dien thoai!', $user->max_mobiles)]);
        endif;

        if($user->checkExistsMobile($user_phone)):
            return Response::json(['success' => false, 'message' => sprintf('So dien thoai %s da ton tai tren he thong!', $user_phone)]);
        endif;

        $user->addMobile($user_phone);

        return Response::json(['success' => true, 'message' => 'Them thanh cong.']);
    }

    public function deleteUserPhone(Request $request){
        $user_phone = $request->get('user_phone');
        $user_phone_id = $request->get('user_phone_id');

        $user = User::find(Auth::user()->id);

        if(!$user):
            return Response::json(['success' => false, 'message' => 'User khong hop le!']);
        endif;

        $user->deleteMobile($user_phone);

        return Response::json(['success' => true, 'message' => 'Xoa thanh cong']);
    }

    public function detailUser(Request $request){
        $user_id = $request->route('id');

        $user = User::find($user_id);

        if(empty($user)):
            return redirect('404');
        endif;

        $current_user_id = Auth::user()->id;

        if($current_user_id != $user_id):
            return redirect('403');
        endif;

        $user_transaction = new UserTransaction();
        $transactions = $user_transaction->newQuery()
            ->where([
                'user_id' => $user_id
            ])
            ->orderBy('id', 'desc')
            ->get();

        $user_mobiles = $user->findByMobiles();

        return view('user_detail', [
            'page_title' => "Thong tin nhan vien [" . $user->email . "]",
            'user' => $user,
            'transactions' => $transactions,
            'user_mobiles' => $user_mobiles
        ]);

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

        return view('user_form', [
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
