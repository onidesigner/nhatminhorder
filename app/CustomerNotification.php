<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 13/04/2017
 * Time: 21:36
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;


class CustomerNotification extends Model
{
    protected $table = 'customer_notification';

    const CUSTOMER_NOTIFICATION_READ = 'READ';
    const CUSTOMER_NOTIFICATION_VIEW = 'VIEW';
    const CUSTOMER_NOTIFICATION_IS_READ = 'IS_READ'; // ddax ddoc

    const TYPE_ORDER = 'ORDER'; // thong bao tren donw
    const TYPE_FINANCE = 'FINANCE'; // thong bao tai chinh
    const TYPE_INFO_NEW = 'INFO_NEW'; // thong bao chung ve tin tuc moi
    const TYPE_PACKAGE = 'PACKAGE'; // thong bao kien hang
    const TYPE_COMPLAINT = 'COMPLAINT'; // thong bao khieu nai
    const TYPE_ORDER_CHAT = 'CHAT'; // thong bao khach chat tren don

    public function createNewNotification($user_id , $order_id , $array_data){

        $result  = $this->insert([
            'order_id' => $order_id,
            'user_id' => $user_id,
            'notification_content' => $array_data['notification_content'],
            'type' => $array_data['type'],
            'is_view' => $array_data['is_view'],
            'section' => $array_data['section'],
            'created_time' =>   date('Y-m-d H:i:s',time())
        ]);

        if(!$result){
            return false;
        }
        return true;

    }

    /**
     * hàm này để lưu lại thông báo của quản trị cho kh
     * nghĩa là khách hàng phải nhận được thông báo n
     * khi quản trị comment thì lưu vào trong này
     * lưu lại comment này để khách hàng có thể nhận được thông báo này
     *  thêm vào bảng follow_user đơn hàng của khách nào và ai đang theo dõi
     * @param Order $order
     * @param $title_notification
     * @param $notification_content
     * @return bool
     */
    public static function notificationCustomerCreateByFollow($order,$title_notification,$notification_content,$type){

        $notify = new  CustomerNotification();

        $notify_customer_exists = Notification::where([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'is_view' => self::CUSTOMER_NOTIFICATION_VIEW
        ])->count();

        if($notify_customer_exists == 0){

            $notify->order_id = $order->id;
            $notify->user_id = $order->user_id; // khách hàng chủ sở hữu của đơn hàng
            $notify->notification_content = $notification_content;
            $notify->title = $title_notification;
            $notify->is_view = self::CUSTOMER_NOTIFICATION_VIEW;
            $notify->type = $type;
            $notify->section = User::SECTION_CUSTOMER;
            $notify->created_time =  date('Y-m-d H:i:s',time());
            $notify->save();

        }

        $notify_buyer_exists = Notification::where([
            'order_id' => $order->id,
            'buyer_id' => $order->paid_staff_id,
            'is_view' => self::CUSTOMER_NOTIFICATION_VIEW
        ])->count();

        if($notify_buyer_exists == 0){
            $notify->order_id = $order->id;
            $notify->buyer_id = $order->paid_staff_id; // nguoiwf mua hang cuar don hang
            $notify->notification_content = $notification_content;
            $notify->title = $title_notification;
            $notify->is_view = self::CUSTOMER_NOTIFICATION_VIEW;
            $notify->type = $type;
            $notify->section = User::SECTION_CUSTOMER;
            $notify->created_time =  date('Y-m-d H:i:s',time());
            $notify->save();
        }


        if(!$notify){
            return false;
        }
        return true;
    }


    /**
     * comment duoc tao boi nguoi mua hang
     * + nguoi theo doi nhan duoc
     * +nguoi so huu don hang cung nhan duoc
     * @param $order
     * @param $title_notification
     * @param $notification_content
     * @param $type
     * @param $follow_user
     * @return bool
     */
    public static function  notificationCustomerCreateByBuyer($order,$title_notification,$notification_content,$type,$follow_user){
        
        $notify = new  CustomerNotification();

        $notify->follow_user = $follow_user->id; // người theo dõi đơn hàng
        $notify->order_id = $order->id;
        $notify->user_id = $order->user_id; // khách hàng chủ sở hữu của đơn hàng
        $notify->notification_content = $notification_content;
        $notify->title = $title_notification;
        $notify->is_view = self::CUSTOMER_NOTIFICATION_VIEW;
        $notify->type = $type;
        $notify->section = User::SECTION_CUSTOMER;
        $notify->created_time =  date('Y-m-d H:i:s',time());
        $notify->save();

        if(!$notify){
            return false;
        }
        return true;
    }

    /**
     * @param Order $order
     * @param $title_notification
     * @param $notification_content
     * @param $type
     * @param $follow_user
     * @return bool
     */
    public static function notificationCraneCreateByCustomer($order,$title_notification,$notification_content,$type,$follow_user){

        $notify = new  CustomerNotification();

        $notify->follow_user = $follow_user->id; // người theo dõi đơn hàng
        $notify->order_id = $order->id; // id của đơn hàng
        $notify->buyer_id = $order->paid_staff_id; // đơn hàng này của bạn nào phụ trách
        $notify->notification_content = $notification_content;
        $notify->type = $type;
        $notify->title = $title_notification;
        $notify->is_view = self::CUSTOMER_NOTIFICATION_VIEW;
        $notify->section = User::SECTION_CRANE;
        $notify->created_time =  date('Y-m-d H:i:s',time());
        $notify->save();




        if(!$notify){
            return false;
        }
        return true;

    }

    /**@author : giangnh
     * hàm này được viết ra để check xem hàm có đủ điều kiện để
     * hiển thị thông báo notification hay ko
     * neu tren cung 1 don hang , ma chat tren don do , nhuwng nguoi co
     * lien qua chua doc thi ko tao them thong bao nua
     * @param $order_id
     * @param $user_id
     * @return bool
     */
    public static function checkNotify($order_id , $user_id){

        $check_notification = CustomerNotification::where([
            ['order_id', '=', $order_id],
            ['user_id', '=', $user_id], // đơn hàng này của ai
            ['is_view', '=', self::CUSTOMER_NOTIFICATION_VIEW],
            ['type', '=', self::TYPE_ORDER_CHAT],

        ])->count();

        if($check_notification > 0){
            return false;
        }
        return true; // đủ điều kiện để tạo thông báo mới
    }

    /**
     * ham check nguoi theo doi don thif hien thi don
     * @param $order_id
     * @param $follow_id
     * @return bool
     */
    public static function checkNotifyByFollow($order_id,$follow_id){
        $check_notification = CustomerNotification::where([
            ['order_id', '=', $order_id],
            ['follow_user', '=', $follow_id],
            ['is_view', '=', self::CUSTOMER_NOTIFICATION_VIEW],
            ['type', '=', self::TYPE_ORDER_CHAT],

        ])->count();

        if($check_notification > 0){
            return false;
        }
        return true; // đủ điều kiện để tạo thông báo mới
    }

    /**
     * kiểm tra notification cho người mua (người phụ trách đơn hàng đó)
     * nếu đã có thì ko hiển thị thêm nữa
     * @param $order_id
     * @param $buyer_id
     * @return bool
     */
    public function checkNotificationBuyer($order_id,$buyer_id){
        $check_notification = CustomerNotification::where([
            ['order_id', '=', $order_id],
            ['buyer_id', '=', $buyer_id],
            ['is_view', '=', self::CUSTOMER_NOTIFICATION_VIEW],
            ['type', '=', self::TYPE_ORDER_CHAT],

        ])->count();

        if($check_notification > 0){
            return false;
        }
        return true; // đủ điều kiện để tạo thông báo mới
    }

}