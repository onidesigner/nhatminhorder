<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $table = 'system_config';

    protected $system_config_data = [
        [
            'field_name' => 'Ten website',
            'key' => 'website_name'
        ],
        [
            'field_name' => 'Ten website 1',
            'key' => 'website_name1'
        ],
        [
            'field_name' => 'Ten website 2',
            'key' => 'website_name2'
        ]
    ];

    public function showTable(){
        return $this->system_config_data;
    }

    public function updateData($data_insert){
        if(count($data_insert)):
            $this->newQuery()->delete();

            $this->newQuery()->insert($data_insert);
        endif;



        return true;
    }
}
