<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 20/06/2017
 * Time: 11:34
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $table='customer_system_notification';

    const TYPE_ORDER = 'ORDER';
    const TYPE_COMPLAINT = 'COMPLAINT';
    const TYPE_FINANCE = 'FINANCE' ;
    const TYPE_VIEW = 'VIEW';
    const TYPE_READ = 'READ';
    const STATUS_VIEWED = "WAS_VIEW";
    const STATUS_READ = "WAS_READ";

    /**
     * luu vafo bang khi co dau hieu chat
     * @param $object
     * @param $follower
     * @param $title
     * @param $notification_content
     */
    public function createSystemNotificationChat($object,$follower,$title,$notification_content){

       $system_notification = SystemNotification::where([
            'object_id' => $object->id,
            'follower_id' => $follower->id,
            'type' => self::TYPE_READ,
            'notify_status' => self::TYPE_READ
        ])->get();

        
        if(count($system_notification) == 0){
            $this->object_id = $object->id;
            $this->object_type = self::TYPE_ORDER;
            $this->follower_id = $follower->id;
            $this->title = $title;
            $this->notification_content = $notification_content;
            $this->type = self::TYPE_READ;
            $this->notify_status = self::TYPE_READ;

            $this->save();
        }

    }

    /**
     * Thông báo tài chính cho khách hàng
     * @param $follower
     * @param $title
     * @param $notification_content
     */
    public function createSystemNotificationFinance($follower,$title,$notification_content){
        $this->object_type = self::TYPE_FINANCE;
        $this->follower_id = $follower->id;
        $this->title = $title;
        $this->notification_content = $notification_content;
        $this->type = self::TYPE_VIEW;
        $this->notify_status = self::TYPE_VIEW;

        $this->save();
        
    }

    /**
     * neu chuyen doi trang thai
     * @param $object
     * @param $follower
     * @param $title
     * @param $notification_content
     */
    public function createSystemNotificationOrderStatus($object,$title,$notification_content){
        $user_follower = UserFollowObject::where([
            'object_id' => $object->id,
            'object_type' => self::TYPE_ORDER,
            'status' => UserFollowObject::STATUS_ACTIVE
        ])->get();

        foreach ($user_follower as $item_user){
            $this->object_id = $object->id;
            $this->object_type = self::TYPE_ORDER;
            $this->follower_id = $item_user->id;
            $this->title = $title;
            $this->notification_content = $notification_content;
            $this->type = self::TYPE_VIEW;
            $this->notify_status = self::TYPE_VIEW;

            $this->save();
        }


    }

}