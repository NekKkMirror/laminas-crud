<?php
namespace Api;

use Api\Controller\v1\Task\Factory\TaskControllerFactory;
use Api\Controller\v1\Task\TaskController;
use Api\Service\Task\Factory\TaskServiceFactory;
use Api\Service\Task\TaskService;
use Api\Service\Task\TaskServiceInterface;
use Db\Repository\Task\TaskRepository;
use Db\Repository\Task\TaskRepositoryInterface;
use Laminas\Router\Http\Segment;
use Laminas\View\Renderer\JsonRenderer;
use Laminas\View\Strategy\JsonStrategy;

return [
	'router' => [
		'routes' => [
			'api-todo' => [
				'type' => Segment::class,
				'options' => [
					'route' => '/api/v1/todo[/:task_id]',
					'constraints' => [
						'task_id' => '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}',
					],
					'defaults' => [
						'controller' => Controller\v1\Task\TaskController::class,
					],
				],
			],
		],
	],
	
	'controllers' => [
		'factories' => [
			TaskController::class => TaskControllerFactory::class,
		],
	],
	
	'view_manager' => [
		'strategies' => [
			'ViewJsonStrategy'
		],
	],
	
	'service_manager' => [
		'aliases' => [
			TaskServiceInterface::class => TaskService::class,
			TaskRepositoryInterface::class => TaskRepository::class,
		],
		'factories' => [
			'ViewJsonStrategy' => static function () {
				$jsonRenderer = new JsonRenderer();
				
				return new JsonStrategy($jsonRenderer);
			},
			TaskService::class => TaskServiceFactory::class,
		],
	],

];