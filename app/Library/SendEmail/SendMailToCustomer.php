<?php
namespace App\Library\SendEmail;
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 22/05/2017
 * Time: 13:28
 */
use App\SendEmailCustomerQueue;
use Illuminate\Support\Facades\Log;

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

        $mail->setFrom($my_gmail,'Nhatminh247.vn');

        $mail->addReplyTo($my_gmail, 'Nhatminh247.vn');
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

    /**
     * hàm gửi mail khi hàng nhập kho phân phối
     * @param $email_queue_id
     * @param $customer_mail
     * @param $body_content
     * @throws phpmailerException
     */
    public static function sendEmailWhenImportPackage($email_queue_id,$customer_mail,$body_content){
        $my_gmail = 'nhatminh247.vn@gmail.com';
        $my_pass = 'nhatminh2017';

        $mail = new PHPMailer;
        $mail->CharSet = "UTF-8";

        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = $my_gmail;
        $mail->Password = $my_pass;

        $mail->setFrom($my_gmail,'Nhatminh247.vn');

        $mail->addReplyTo($my_gmail, 'Nhatminh247.vn');
        #địa chỉ mail của nơi nhận
        $mail->addAddress($customer_mail, '');
        $mail->Subject = 'Nhatminh247.vn thông báo hàng đã nhập kho phân phối';

        // Nội dung của email
        $mail->msgHTML('
        <!DOCTYPE html>
            <html lang="en">
                <head>  
                    <meta charset="UTF-8">
                    <title>Nhatminh247.vn thông báo</title>
                </head>
                
                <body>
                <div class="center">
                    <p align="center">'.$body_content.'</p>
                    <p><strong>Trân trọng !</strong></p>
                    <p><strong>nhatminh247.vn</strong></p>
                </div>
                
                </body>
            </html>
            ');
        //send the message, check for errors
        if (!$mail->send()) {
            Log::info('send_mail_fail',[$mail->ErrorInfo]);
            SendEmailCustomerQueue::where('id',$email_queue_id)->update([
                'send_status' => SendEmailCustomerQueue::SUCCESS
            ]);

        } else {
            //echo "Message sent!";
            // lấy ra mã mail vừa gửi
            SendEmailCustomerQueue::where('id',$email_queue_id)->update([
                'send_status' => SendEmailCustomerQueue::SUCCESS
            ]);
        }

    }

}