<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaidSaleSetting extends Model
{
    protected $table = 'user_paid_sale_setting';

    /**
     * @author vanhs
     * @desc Lay phan tram hoa hong nhan cua nhan vien mua hang theo tung thang
     * @param $crane_value_setting_list
     * @param $month_check
     * @return int
     */
    public static function getValuePercentWithCraneAndTime($crane_value_setting_list, $month_check){
        $percent = 0;
        if(isset($crane_value_setting_list) && !count($crane_value_setting_list)){
            return $percent;
        }

        foreach($crane_value_setting_list as $crane_value_setting_list_item){
            if(strtotime($month_check) >= strtotime($crane_value_setting_list_item->activated_at)
                && strtotime($month_check) <= strtotime($crane_value_setting_list_item->deadlined_at)){
                $percent = $crane_value_setting_list_item->rose_percent;
                break;
            }
        }
        return $percent;
    }

    /**
     * @author vanhs
     * @desc Lay luong co ban cua nhan vien mua hang theo tung thang
     * @param $crane_value_setting_list
     * @param null $month_check
     * @return int
     */
    public static function getSalaryWithCraneAndTime($crane_value_setting_list, $month_check = null){
        $salary = 0;

        if(isset($crane_value_setting_list) && !count($crane_value_setting_list)){
            return $salary;
        }

        if(!$month_check){
            $month_check = sprintf("%s-%s-01 00:00:00", date('Y'), date('m'));
        }else{
            $month_temp = explode('_', $month_check);
            $month_check = sprintf("%s-%s-01 00:00:00", $month_temp[1], $month_temp[0]);
        }

        foreach($crane_value_setting_list as $crane_value_setting_list_item){
            if(strtotime($month_check) >= strtotime($crane_value_setting_list_item->activated_at)
                && strtotime($month_check) <= strtotime($crane_value_setting_list_item->deadlined_at)){
                $salary = $crane_value_setting_list_item->salary_basic;
                break;
            }
        }

        return $salary;
    }

    /**
     * @author vanhs
     * @desc So tien hoa hong thuc nhan tren moi don hang tinh bang vnd
     * @param $amount
     * @param $percent
     * @return float|int
     */
    public static function getRealAmountVnd($amount, $percent){
        return $amount * $percent / 100;
    }

    /**
     * @author vanhs
     * @desc So tien hoa hong thuc nhan tren moi don hang tinh bang nhan dan te
     * @param $amount
     * @param $percent
     * @return float|int
     */
    public static function getRealAmountNdt($amount, $percent){
        return $amount * $percent / 100;
    }
}
