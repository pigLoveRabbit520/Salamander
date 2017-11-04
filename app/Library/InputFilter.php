<?php
/**
 * Authoer: salamander
 * Date: 2017/1/13
 * Time: 14:48
 * 用户输入过滤类
 */

namespace App\Library;


class InputFilter {
    /**
     * 获取过滤后GET参数
     * @param string $key
     * @param null string $default 默认值
     * @param null $filter
     * @return mixed
     * @throws \Exception
     */
    public static function get($key, $default = null, $filter = null) {
        return self::getValue($key, $default, $filter, 'GET');
    }

    /**
     * 获取过滤后POST参数
     * @param string $key
     * @param null string $default 默认值
     * @param null $filter
     * @return mixed
     * @throws \Exception
     */
    public static function post($key, $default = null, $filter = null) {
        return self::getValue($key, $default, $filter, 'POST');
    }

    /**
     * @param string $key
     * @param string $default
     * @param string $filter 过滤方法
     * @param string $type http类型
     * @return mixed
     * @throws \Exception
     */
    private static function getValue($key, $default, $filter, $type)
    {
        if (!$key) {
            throw new \Exception('key can not be empty');
        }
        if (empty($filter)) {
            $filter = get_config_arr()['default_filter'];
        }
        if (!$filter) {
            throw new \Exception('filter can not be empty');
        }
        switch ($type) {
            case 'GET':
                $input = $_GET;
                break;
            case 'POST':
                $input = $_POST;
                break;
            default:
                $input = [];
                break;
        }
        if(!array_key_exists($key, $input) || !isset($input[$key]) ) {
            return $default;
        }
        $value = $input[$key];
        $filters = array_filter(explode(',', $filter));
        foreach ($filters as $filter) {
            $value = $filter($value);
        }
        return $value;
    }
}