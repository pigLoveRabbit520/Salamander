<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header,
        'determineRouteBeforeAppMiddleware' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/template/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
    'notFoundHandler' => function ($c) {
        return function ($request, $response) use ($c) {
            return $c->renderer->render($response ->withStatus(404), '404.html');
        };
    },
    // 默认过滤方式
    'default_filter' => 'htmlspecialchars,trim'
];