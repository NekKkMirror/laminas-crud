<?php

return [
	
	'modules' => [
		'Laminas\Router',
		'Laminas\DeveloperTools',
		'Api',
		'Db'
	],
	
	'module_listener_options' => [
		'config_glob_paths' => [
			'config/autoload/{,*.}{global,local,development}.php',
			'module/*/config/module.config.php',
		],
		'module_paths' => [
			'./module',
			'./vendor',
		],
	],
];