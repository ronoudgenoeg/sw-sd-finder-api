<?php
require_once 'vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => false,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);
require_once('bootstrap.php');
require_once('src/routes.php');
$app->run();
