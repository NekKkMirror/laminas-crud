<?php
if (!defined('APP_NODE_ENV')) {
	define('APP_NODE_ENV', 'development');
}

if (APP_NODE_ENV === 'development') {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$appConfig = require 'config/application.config.php';
Laminas\Mvc\Application::init($appConfig)->run();