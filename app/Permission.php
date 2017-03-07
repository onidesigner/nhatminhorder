<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    protected $table = 'permissions';

    #region -- User permission --
    const PERMISSION_USER_VIEW = 'USER_VIEW';
    const PERMISSION_USER_VIEW_LIST = 'USER_VIEW_LIST';
    #endregion

    #region -- System permission --
    const PERMISSION_UPDATE_SYSTEM_CONFIG = 'UPDATE_SYSTEM_CONFIG';
    const PERMISSION_DELETE_ROLE = 'DELETE_ROLE';
    const PERMISSION_CREATE_ROLE = 'CREATE_ROLE';
    const PERMISSION_VIEW_ROLE = 'VIEW_ROLE';

    #endregion

    public static $permissions = array(
        'user_role_permission' => array(
            'label' => 'Người dùng, nhóm, phân quyền',
            'permissions' => array(
                self::PERMISSION_USER_VIEW_LIST => array(
                    'label' => 'Xem danh sách người dùng',
                    'description' => 'Quyền cho phép quản trị viên xem danh sách người dùng',
                )
            ),
        ),

        'system_permission' => array(
            'label' => 'Cấu hình hệ thống ',
            'permissions' => array(
                self::PERMISSION_UPDATE_SYSTEM_CONFIG => array(
                    'label' => 'Chỉnh sửa cấu hình chung trên hệ thống ',
                    'description' => 'Quyền cho phép quản trị viên chỉnh sửa cấu hình chung trên hệ thống',
                ),
                self::PERMISSION_CREATE_ROLE => array(
                    'label' => 'Quyền tạo nhóm',
                    'description' => '',
                ),
                self::PERMISSION_VIEW_ROLE => array(
                    'label' => 'Quyền xem chi tiết & thao tác chỉnh sửa thông tin nhóm',
                    'description' => '',
                ),
                self::PERMISSION_DELETE_ROLE => array(
                    'label' => 'Quyền xoá nhóm',
                    'description' => '',
                )
            ),
        ),


    );

    /**
     * @author vanhs
     * @desc Ham kiem tra 1 user co quyen chi dinh hay khong?
     * @param $permission_code
     * @return bool
     */
    public static function isAllow($permission_code){
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $user_section = Auth::user()->section;

        if(in_array($user_email, User::$god)):
            return true;
        endif;

        if($user_section == User::SECTION_CUSTOMER):
            return false;
        endif;

        $user_permission = Cache::get("user_permission_{$user_id}");
        if(empty($user_permission)):
            $user_permission = [];
        endif;

        if(in_array($permission_code, $user_permission)):
            return true;
        endif;

        return false;
    }
}
