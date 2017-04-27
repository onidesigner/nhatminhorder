<?php
namespace App\Library\Sms;


/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 20/04/2017
 * Time: 01:25
 */

class SendSmsToCustomer
{
    public function sendSms($phones, $content){
        $sms = new SpeedSMSAPI();
        $result = $sms->sendSMS($phones, $content, SpeedSMSAPI::SMS_TYPE_CSKH, "");
        return  $result;
    }

    public static function getStatus($status){
        switch ($status) {
            case "SEND_SUCCESS":
                return "Gửi thành công";
            case "SEND_NOT_SUCCESS" :
                return "Gửi không thành công";
            case "ERROR" :
                return "Gửi Lỗi";
            default:
              return  '';
        }
    }
}