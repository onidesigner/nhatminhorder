<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\UserMobile;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = 'users';

    public $max_mobiles = 3;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    const SECTION_CRANE = 'CRANE';
    const SECTION_CUSTOMER = 'CUSTOMER';

    public static $status_list = [
        self::STATUS_ACTIVE => 'Kích hoạt',
        self::STATUS_INACTIVE => 'Ngừng kích hoạt'
    ];

    public static $god = [
        'hosivan90@gmail.com'
    ];

    public static $section_list = [
        self::SECTION_CRANE => 'Quản trị viên',
        self::SECTION_CUSTOMER => 'Khách hàng',
    ];

    protected static function statusList(){
        return self::$status_list;
    }

    protected static function sectionList(){
        return self::$section_list;
    }

    public static function getStatusName($status = null){
        return empty(self::$status_list[$status]) ? '' : self::$status_list[$status];
    }

    public static function getSectionName($section = null){
        return empty(self::$section_list[$section]) ? '' : self::$section_list[$section];
    }

    public static function genCustomerCode(){
        $vowel = array('A', 'E', 'I', 'O', 'U');
        $consonants = array('B', 'C', 'D', 'G', 'H', 'K', 'M', 'P', 'R', 'S', 'T', 'V', 'X');
        $times = 0;
        do {
            $char_part = $consonants[array_rand($consonants)] . $vowel[array_rand($vowel)];
            $number_part = Util::getLuckyNumber(4);
            $code = "{$char_part}{$number_part}";

            $check = DB::table('users')->where('code', $code)->value('code');
            $times++;
        } while ($check && $times <= 8);

        return $code;
    }

    public function updateAccountBalance($amount, $user_id){
        try{
            DB::beginTransaction();

            $this->newQuery()->where([
                'id' => $user_id,
            ])->update([
                'account_balance' => DB::raw("account_balance+{$amount}")
            ]);

            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function findByMobiles(){
        return UserMobile::where(['user_id' => $this->id])->get();
    }

    public function addMobile($mobile){
        return UserMobile::insert([
            'user_id' => $this->id,
            'mobile' => $mobile,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function deleteMobile($mobile){
        return UserMobile::where([
            'user_id' => $this->id,
            'mobile' => $mobile
        ])->delete();
    }

    public function checkExistsMobile($mobile){
        return UserMobile::where([
            'mobile' => $mobile
        ])->first();
    }

    public function checkMaxMobile(){
        return UserMobile::where([
            'user_id' => $this->id
        ])->count() >= $this->max_mobiles;
    }


    public function address(){
        return $this->hasMany('App\UserAddress', 'order_id');
    }

    public function mobile(){
        return $this->hasMany('App\UserMobile', 'order_id');
    }

    public function role(){
        return $this->hasMany('App\UserRole', 'user_id');
    }

    
}
