<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Location;

class UserAddress extends Model
{
    protected $table = 'user_address';

    public $user_address_max = 5;

    public function findByUserId($user_id){
        return $this->newQuery()
            ->select('*')
            ->where([
                'user_id' => $user_id
            ])
            ->orderBy('is_default', 'desc')->get();
    }

    public function checkMaxUserAddress($user_id){
        $total = $this->newQuery()
            ->select('id')
            ->where(['user_id' => $user_id])
            ->count();
        if($total < $this->user_address_max):
            return true;
        endif;

        return false;
    }

    public function addNewUserAddress($send_data){
        $user_address_id = $send_data['user_address_id'];
        unset($send_data['user_address_id']);
        unset($send_data['_token']);

        if(!$user_address_id):
            $this->newQuery()->insert($send_data);
        else:
            $this->newQuery()
                ->where(['id' => $user_address_id, 'user_id' => $send_data['user_id']])
                ->update($send_data);
        endif;
        return true;
    }

    public function deleteUserAddress($id, $user_id){
        return $this->newQuery()
            ->where(['id' => $id, 'user_id' => $user_id])
            ->delete();
    }

    public function setDefaultUserAddress($id, $user_id){
        $this->newQuery()
            ->where(['user_id' => $user_id])
            ->update([
                'is_default' => 0
            ]);

        $this->newQuery()
            ->where(['id' => $id, 'user_id' => $user_id])
            ->update([
                'is_default' => 1
            ]);

        return true;
    }
}
