<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 20/06/2017
 * Time: 11:10
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UserFollowObject extends Model
{
    protected $table = 'user_follow_object';

    const TYPE_ORDER = 'ORDER';
    const TYPE_COMPLAINT = 'COMPLAINT';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * assign nguoi theo doi don
     * @param $order
     * @param $follow_user
     */
    public  function createUserFollow($order,$follow_user){
        $this->object_id = $order->id;
        $this->object_type = self::TYPE_ORDER;
        $this->follower_id = $follow_user->id;
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

}