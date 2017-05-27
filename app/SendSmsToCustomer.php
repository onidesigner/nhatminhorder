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

    const NOT_YET = 'NOT_YET';// chưa gửi t
    const SUCCESS = 'SUCCESS'; // gưi thành cong
    const FAIL = 'FAIL'; // guiwr that bai

    /**
     * save giá trị 
     * @param $data
     */
    public function CustomerSms($data){
        $this->order_id = $data['order_id'];
        $this->phone = $data['phone'];
        $this->content = $data['content'];
        $this->user_id = $data['user_id'];
        $this->send_status = $data['send_status'];
        $this->save();
    }
}