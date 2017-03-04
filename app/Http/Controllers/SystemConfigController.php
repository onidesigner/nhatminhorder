<?php

namespace App\Http\Controllers;

use App\Permission;
use App\SystemConfig;

use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SystemConfigController extends Controller
{

    public function updateUserRole(Request $request){
        if(Auth::user()->section == User::SECTION_CUSTOMER):
            return Response::json(['success' => true]);
        endif;

        $role = Role::find($request->get('role_id'));
        $user_id = $request->get('user_id');
        $role_id = $request->get('role_id');

        if(!$role):
            return Response::json(['success' => true]);
        endif;

        $user_role = new UserRole();

        $action = $request->get('action');
        switch ($action):
            case "add":

                $check_exists = $user_role->newQuery()
                    ->where(['user_id' => $user_id, 'role_id' => $role_id])
                    ->first();

                if(!$check_exists):
                    $user_role->newQuery()->insert([
                        'user_id' => $user_id,
                        'role_id' => $role_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                endif;


                break;
            case "remove":
                $user_role->newQuery()->where([
                    'user_id' => $user_id,
                    'role_id' => $role_id
                ])->delete();

                break;
        endswitch;

        return Response::json(['success' => true]);
    }

    public function savePermission(Request $request){
        if(Auth::user()->section == User::SECTION_CUSTOMER):
            return Response::json(['success' => true]);
        endif;

        $role = Role::find($request->get('role_id'));

        if(!$role):
            return Response::json(['success' => true]);
        endif;

        $permission_data = [];

        $permission_params = $request->get('permission');
        foreach($permission_params as $permission_param):
            $permission_data[] = [
                'role_id' =>  $request->get('role_id'),
                'code' => $permission_param,
                'created_at' => date('Y-m-d H:i:s')
            ] ;
        endforeach;

        $permission = new Permission();
        $permission->newQuery()->where(['role_id' => $request->get('role_id')])->delete();

        if(count($permission_data)):
            $permission->newQuery()->insert($permission_data);
        endif;

        return Response::json(['success' => true]);
    }

    public function updateRole(Request $request){
        $data = $request->all();
        $role_id = $data['role_id'];

        if(Auth::user()->section == User::SECTION_CUSTOMER):
            return Response::json(['success' => true]);
        endif;

        $role = Role::find($request->get('role_id'));

        if(!$role):
            return Response::json(['success' => true]);
        endif;

        $role = new Role();

        $role->newQuery()->where([
            'id' => $role_id
        ])->update([
            'label' => $data['label'],
            'state' => $data['state'],
            'description' => $data['description'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect("setting/role/$role_id");
    }

    public function roleDetail(Request $request){

//        $permission = new Permission();
//        $check = $permission->isAllow(Permission::PERMISSION_USER_VIEW_LIST);
//        var_dump($check);

        if(Auth::user()->section == User::SECTION_CUSTOMER):
            redirect('403');
        endif;

        $id = $request->route('id');
        $role = Role::find($id);

        if(!$role):
            redirect('404');
        endif;

        $role_name = $role->label;

        $user_role = new UserRole();
        $user = new User();

        #region -- danh sach cac user thuoc role nay
        $users_in_role_array = $user_role->newQuery()->select('user_id')->where([
            'role_id' => $id
        ])->get()->toArray();

        $users_ids_in_role = [];
        if($users_in_role_array):
            foreach($users_in_role_array as $k => $v):
                $users_ids_in_role[] = $v['user_id'];
            endforeach;
        endif;
        $users_ids_in_role[] = 0;

        $users_in_role = $user->newQuery()->select('*')->where([
            'section' => User::SECTION_CRANE,
            'status' => User::STATUS_ACTIVE,
        ])->whereIn('id', $users_ids_in_role)->get()->toArray();

        #endregion

        #region -- danh sach cac user khong thuoc ve role nay --
        $users_not_in_role = $user->newQuery()->select('*')->where([
            'section' => User::SECTION_CRANE,
            'status' => User::STATUS_ACTIVE,
        ])->whereNotIn('id', $users_ids_in_role)->get()->toArray();
        #endregion

        #region -- danh sach cac quyen thuoc role nay --
        $permission = new Permission();
        $permissions_role = $permission->newQuery()->select('code')->where([
            'role_id' => $id
        ])->get()->toArray();

        $permissions_role_list = [];
        if($permissions_role):
            foreach($permissions_role as $key => $value):
                $permissions_role_list[] = $value['code'];
            endforeach;
        endif;
        #endregion

        $data = [
            'page_title' => sprintf("Chi tiet nhom [%s]", $role_name),
            'role' => $role,
            'role_id' => $id,
            'users_not_in_role' => $users_not_in_role,
            'users_in_role' => $users_in_role,
            'permissions_role' => $permissions_role_list,
            'permissions' => Permission::$permissions
        ];
        return view('role_detail', $data);
    }

    public function addRole(Request $request){
        $data_insert = $request->all();
        $data_insert['created_at'] = date('Y-m-d H:i:s');

        unset($data_insert['_token']);

        if(Auth::user()->section == User::SECTION_CUSTOMER):
            return Response::json(['success' => true]);
        endif;

        $validator = Validator::make($data_insert, [
            'label' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return Response::json(array('success' => false, 'message' => implode('<br>', $errors) ));
        }

        $role = new Role();
        $role->newQuery()->insert($data_insert);

        return Response::json(['success' => true]);
    }

    public function deleteRole(Request $request){
        $id = $request->get('id');

        if(Auth::user()->section == User::SECTION_CUSTOMER):
            return Response::json(['success' => true]);
        endif;

        Role::find($id)->delete();

        return Response::json(['success' => true]);
    }

    public function roles(){
        $role = new Role();
        $roles = $role->newQuery()->orderBy('created_at', 'desc')->get()->toArray();
        $data = [
            'page_title' => "Nhom & phan quyen",
            'roles' => $roles,
            'permissions' => Permission::$permissions
        ];
        return view('role', $data);
    }

    public function getList(Request $request){
        $data_inserted = [];

        $data_inserted_array = SystemConfig::all()->toArray();

        $data_inserted_array = (array)$data_inserted_array;
        foreach($data_inserted_array as $data_inserted_array_item):
            $data_inserted[$data_inserted_array_item['config_key']] = $data_inserted_array_item['config_value'];
        endforeach;

        $system_config = new SystemConfig();

//        var_dump($data_inserted);

        $data = [
            'page_title' => "Cau hinh chung he thong",
            'data' => $system_config->showTable(),
            'data_inserted' => $data_inserted,
            'save' => $request->get('save')
        ];
        return view('system_config', $data);
    }

    public function update(Request $request){

        $data_send = $request->all();

        unset($data_send['_token']);

        $system_config = new SystemConfig();

        $data_insert = [];

        foreach($data_send as $key => $data_send_item):

            $data_insert[] = [
                'config_key' => $key,
                'config_value' => $data_send_item ? $data_send_item : ''
            ];
        endforeach;

//        var_dump($data_insert);

        $system_config->updateData($data_insert);

        return redirect("setting?save=success");
    }
}
