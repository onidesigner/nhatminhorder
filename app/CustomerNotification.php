<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 13/04/2017
 * Time: 21:36
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    protected $table = 'customer_notification';

    public function createNewNotification($user_id , $order_id , $array_data){

        $result  = $this->insert([
            'order_id' => $order_id,
            'user_id' => $user_id,
            'notification_content' => $array_data['notification_content'],
            'type' => $array_data['type'],
            'is_view' => $array_data['is_view'],
            'created_time' =>   date('Y-m-d H:i:s',time())
        ]);

        if(!$result){
            return false;
        }
        return true;

    }

}