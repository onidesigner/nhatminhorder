<?php
namespace App\Library\SendEmail;
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 22/05/2017
 * Time: 13:28
 */
include('class.smtp.php');
include "class.phpmailer.php";
class SendMailToCustomer
{
    public static function sendMail(){
        $my_gmail = 'nhatminh247.vn@gmail.com';
        $my_pass = 'nhatminh2017';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $my_gmail;
        $mail->Password = $my_pass;

        $mail->setFrom($my_gmail,$my_pass);

        $mail->addReplyTo($my_gmail, $my_pass);
        #địa chỉ mail của nơi nhận
        $mail->addAddress('nguyengiangdhxd@gmail.com', 'Mail đầu tiên');
        $mail->Subject = 'PHPMailer GMail SMTP test';

        $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        $mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }

    }

}