<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemConfig extends Model
{
    const CACHE_SYSTEM_CONFIG_KEY = 'system_config';

    protected $table = 'system_config';

    public static $system_config_data = [
        [
            'field_name' => 'Tối đa địa chỉ nhận hàng khách',
            'key' => 'user_address_max'
        ],
        [
            'field_name' => 'Tối đa số điện thoại khách',
            'key' => 'user_mobile_max'
        ],
        [
            'field_name' => 'Tỉ lệ đặt cọc đơn hàng',
            'key' => 'order_deposit_percent'
        ],

        [
            'field_name' => 'Hiển thị popup ngoài trang chủ?',
            'key' => 'home_page_enable_popup',
            'tag_name' => 'select',
            'options' => [
//                '' => '-- Mời chọn --',
                0 => 'Không hiển thị',
                1 => 'Hiển thị',
            ]
        ],
        [
            'field_name' => 'Tiêu đề popup ngoài trang chủ',
            'key' => 'home_page_title_popup'
        ],
        [
            'field_name' => 'Nội dung popup ngoài trang chủ',
            'key' => 'home_page_content_popup',
            'tag_name' => 'textarea'
        ],
    ];

    public function updateData($data_insert){
        if(count($data_insert)):
            $this->newQuery()->delete();

            $this->newQuery()->insert($data_insert);
        endif;

        return true;
    }

    public static function getConfigValueByKey($key){
        if(empty($key)):
            return null;
        endif;

        $system_config_cache = (array)Cache::get(self::CACHE_SYSTEM_CONFIG_KEY);
        if(!empty($system_config_cache[$key])):
            return $system_config_cache[$key];
        endif;

        return null;
    }
}
