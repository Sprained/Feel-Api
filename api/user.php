<?php

header('Access-Control-Allow-Headers: *');

require __DIR__ . '/../vendor/autoload.php';

use Feel\App\Controller\UserController;

$method = strtolower($_SERVER['REQUEST_METHOD']);

$controller = new UserController();

switch($method) {
    case 'post':
        $controller->register();
        break;
    case 'get':
        $controller->select();
        break;
}