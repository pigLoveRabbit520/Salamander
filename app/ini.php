<?php
/**
 * User: salamander
 * Date: 2016/11/26
 * Time: 15:09
 */

function initWeb() {
    ini_set('memory_limit', '256M');
    set_time_limit(20);
    ini_set('session.name', 'SEOMANEGESESSION');
    // session存入redis
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', 'tcp://127.0.0.1:6379');
    ini_set('session.cookie_lifetime', 24 * 60 * 60);
    // 过期时间2个小时
    ini_set('session.gc_maxlifetime', 24 * 60 * 60);
    ini_set('date.timezone','Asia/Shanghai');
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    session_cache_limiter(false);
    session_start();
    // 修改 X-Powered-By信息
    header('X-Powered-By: Salamander');
}

initWeb();
// load functions
require APP . '/functions.php';
// Instantiate the app
$settings = require APP . '/config.php';
$app = new \Slim\App($settings);

// Set up dependencies
require APP . '/settings/dependencies.php';

// Register middleware
require APP . '/settings/middleware.php';

// Register routes
require APP . '/routes.php';

// Run app
$app->run();