<?php

// 利用nginx路由重写，统一由该文件处理
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// 定义常量
define("ROOT", getcwd() . '/..');
define('APP', ROOT . '/app');

require ROOT . '/vendor/autoload.php';
// 加载php初始化设置文件
require APP . '/ini.php';
