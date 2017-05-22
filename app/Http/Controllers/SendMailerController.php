<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 22/05/2017
 * Time: 11:38
 */

namespace App\Http\Controllers;


use App\Library\SendEmail\SendMailToCustomer;



class SendMailerController extends Controller
{
    
    public function sendEmailToCustomer(){
        $mail = "nguyengiangdhxd@gmail.com";
        SendMailToCustomer::sendMail($mail,'','');
    }
}