<?php
namespace App\Library\SendEmail;
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 22/05/2017
 * Time: 13:28
 */
require('class.smtp.php');
require ("class.phpmailer.php");
class SendMailToCustomer
{
    /**
     * @param $customer_mail
     * @param $path_template
     * @param $patch_attach
     * @throws phpmailerException
     */
    public static function sendMail($customer_mail,$path_template,$patch_attach){

        $my_gmail = 'nhatminh247.vn@gmail.com';
        $my_pass = 'nhatminh2017';

        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = $my_gmail;
        $mail->Password = $my_pass;

        $mail->setFrom($my_gmail,$my_pass);

        $mail->addReplyTo($my_gmail, $my_pass);
        #địa chỉ mail của nơi nhận
        $mail->addAddress($customer_mail, '');
        $mail->Subject = 'Nhatminh247.vn';

        $mail->msgHTML(file_get_contents('../public/template_email/content.html'), dirname(__FILE__));

        //Attach an image file
        $mail->addAttachment('../public/uploads/San_luong_van_chuyen_nhat_minh.xlsx');
        $mail->addAttachment('../public/uploads/ssss.png');

        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }

    }

}