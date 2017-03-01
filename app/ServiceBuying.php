<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceBuying extends Model
{
    protected $table = 'service_buying';

    public function getPriceUnit($total_amount = 0){
        return $this->newQuery()
            ->where([
                'begin' => $total_amount,
                'end' => $total_amount,
                'actived_time' => '',
                'deadlined_time' => '',
                'status' => Service::STATUS_ACTIVE
            ])->first();

    }
}
