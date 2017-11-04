<?php
/**
 * Author: salamander
 * Date: 2016/10/12
 * Time: 11:45
 */

namespace App\Tool;


class Time
{
    /**
     * 获取昨天0点时间戳
     * @return int
     */
    public static function getYesterdayStartTime() {
        return strtotime(date("Y-m-d", strtotime('-1 day')));
    }

    /**
     * 获取昨天24点时间戳，相当于今天的0点
     * @return int
     */
    public static function getYesterdayEndTime() {
        return strtotime(date('Y-m-d'));
    }

    /**
     * 今天开始时间点
     * @return int
     */
    public static function getTodayStartTime() {
        return strtotime(date('Y-m-d'));
    }

    /**
     * 获取今天截止的时间戳
     * @return int
     */
    public static function getTodayEndTime() {
        return strtotime(date("Y-m-d", strtotime('+1 day')));
    }

    /**
     * 获取上周一0点时间戳，避免使用strtotime函数
     * @return int
     */
    public static function getLastWeekStartTime() {
        $days = date('w') == 0 ? 13 : date('w') + 6;
        return strtotime(date('Y-m-d')) - $days * 86400;
    }

    /**
     * 获取上周末24点时间戳，避免使用strtotime函数
     * @return int
     */
    public static function getLastWeekEndTime() {
        return self::getLastWeekEndTime() + 7 * 86400;
    }

    /**
     * 获取上个月开始时间
     * @return int
     */
    public static function getLastMonthStartTime() {
        $str = date('Y-m-01', strtotime('-1 month'));
        return strtotime($str);
    }

    /**
     * 获取上个月结束时间
     * @return int
     */
    public static function getLastMonthEndTime() {
        $str = date('Y-m-t', strtotime('-1 month'));
        return strtotime($str) + 86400;
    }

    /**
     * 获取基于当前时刻的特定时刻
     * @param $step int 回退几小时
     * @return int
     */
    public static function getBackCertainHour($step) {
        $now = strtotime(date('Y-m-d H') . ':00:00');
        return $now - $step * 3600;
    }

    /**
     * 获取回退几天0点的时间戳
     * @param $step int 回退几天
     * @return int
     */
    public static function getBackCertainDay($step) {
        $now = strtotime(date('Y-m-d'));
        return $now - $step * 86400;
    }


    /**
     * 获取本月开始时间
     * @return int
     */
    public static function getThisMonthStartTime() {
        return strtotime(date('Y-m-01'));
    }



}