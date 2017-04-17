<?php
/**
 * Created by PhpStorm.
 * User: goerge
 * Date: 15/04/2017
 * Time: 11:57
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    protected $table = 'complaints';

    // constant status
    const STATUS_CREATE = 'CREATE'; // khoi tao
    const STATUS_ACCEPT = 'ACCEPT'; // chap nhan
    const STATUS_FINISH = 'FINISH'; // hoan thanh
    const STATUS_REJECT = 'REJECT'; // tu cho

    /**
     * create new complaint
     * @param array $complaint
     * @return bool
     */
    public function createComplaint($complaint = array()){

        $result  = $this->insertGetId([
            'order_id' => $complaint['order_id'],
            'customer_id' => $complaint['customer_id'],
            'description' => $complaint['comment'],
            'status' => $complaint['status'], // trang thai la khoi tao
            'title' => $complaint['title'],
            'created_time' =>   date('Y-m-d H:i:s',time())
        ]);

        if(!$result){
            return false;
        }
        return $result;
    }
}