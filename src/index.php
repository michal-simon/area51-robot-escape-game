<?php

namespace Robot;

use Robot\Controllers\EscapeController;
use Robot\Factories\HttpClientFactory;
use Robot\Factories\LoggerFactory;
use Robot\Models\RobotModel;
use Robot\Navigation\Navigator;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Robot\\Factories\\', __DIR__ . '/factories');
$loader->addPsr4('Robot\\Models\\', __DIR__ . '/models');
$loader->addPsr4('Robot\\Controllers\\', __DIR__ . '/controllers');
$loader->addPsr4('Robot\\Navigation\\', __DIR__ . '/navigation');

// Dependency Injection
$logger = LoggerFactory::createInstance();
// ToDo: Move URL to some sort of config file or ENV variable
$client = HttpClientFactory::createInstance($logger, 'https://area51.serverzone.dev');
$model = new RobotModel($client);
$navigator = new Navigator($model, $logger);
$controller = new EscapeController($model, $navigator, $logger);

// ToDo: Move email and salary to some sort of config file or ENV variable
$result = $controller->actionEscape('a@b.com', 999999999);

echo $result;
