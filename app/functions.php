<?php
/**
 * User: salamander
 * Date: 2016/10/11
 * Time: 8:49
 * 自定义公众函数辅助库
 */
function set_api_array($errcode, $errmsg, $res = null) {
    return [
        'errcode' => $errcode,
        'errmsg' => $errmsg,
        'res' => $res
    ];
}

function env($key) {
    static $arr = [];
    if(!$arr) {
        $arr = parse_ini_file(ROOT . '/.env');
    }
    return $arr[$key];
}

/**
 * 获取slim 的配置文件数组
 * @return array|mixed
 */
function get_config_arr() {
    static $arr = [];
    if(!$arr) {
        $arr = require __DIR__ . '/config.php';
    }
    return $arr;
}