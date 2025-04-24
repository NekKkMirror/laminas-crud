<?php
$port = getenv('APP_PORT');

return [
	'swagger' => [
		'servers' => [
			[
				'url' => "http://localhost:${port}/api/v1",
				'description' => 'Base URL for the Task API',
			],
		],
	],
];