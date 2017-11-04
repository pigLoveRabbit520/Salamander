<?php
use \App\Service\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$homePrefix = 'App\Controller\Home\\';


// Routes
$app->get('/',  $homePrefix .  'HomeController:index');




