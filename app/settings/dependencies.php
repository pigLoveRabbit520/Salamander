<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
    static $db = null;
    if(!$db) {
        $dbHost = env('MYSQL_HOST');
        $dbName = env('MYSQL_DBNAME');
        $dbUser = env('MYSQL_USER');
        $dbPass = env('MYSQL_PASSWORD');

        $dbConf = [
            'dsn' => "mysql:dbname={$dbName};host={$dbHost}",
            'username' => "{$dbUser}",
            'password' => "{$dbPass}",
            'charset' => 'utf8'
        ];
        $db = new \App\Library\DB();
        $db->__setup($dbConf);
    }
    return $db;
};

$container['user'] = function ($c) {
    return new \App\Service\User($c);
};
