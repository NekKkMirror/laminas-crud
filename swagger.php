<?php
require 'vendor/autoload.php';

use OpenApi\Annotations\Server;
use OpenApi\Generator;

$config = require 'config/autoload/swagger.global.php';
$servers = $config['swagger']['servers'];

$openapi = Generator::scan([
	__DIR__ . '/module/Api/src/Controller/v1/Task/TaskController.php',
	__DIR__ . '/module/Api/src/Controller/v1/Task/Dto',
]);

$openapi->servers = array_map(static function ($server) {
	return new Server([
		'url' => $server['url'],
		'description' => $server['description'],
	]);
}, $servers);

header('Content-Type: application/json');

echo $openapi->toJson();