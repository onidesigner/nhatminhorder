<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 25/05/2017
 * Time: 13:42
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class SendSmsToCustomer extends Model
{
    protected $table = 'send_sms_to_customer';

    /**
     * save giá trị 
     * @param $data
     */
    public function CustomerSms($data){
        $this->order_id = $data['order_id'];
        $this->phone = $data['phone'];
        $this->content = $data['content'];
        $this->user_id = $data['user_id'];
        $this->save();
    }
}