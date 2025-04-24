<?php
namespace Api\Controller\v1\Task\Factory;

use Api\Controller\v1\Task\TaskController;
use Api\Service\Task\TaskServiceInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class TaskControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TaskController
	{
		$taskService = $container->get(TaskServiceInterface::class);
		return new TaskController($taskService);
	}
}