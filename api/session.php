<?php

header('Access-Control-Allow-Headers: *');

require __DIR__ . '/../vendor/autoload.php';

use Feel\App\Controller\SessionController;

$method = strtolower($_SERVER['REQUEST_METHOD']);

$controller = new SessionController();

switch ($method) {
    case 'post':
        $controller->login();
        break;
}