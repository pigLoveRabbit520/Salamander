<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// 用户是否登录中间件
$app->add(new \App\Middleware\UserLoginMiddleware());