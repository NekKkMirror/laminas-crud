<?php
namespace Api\Service\Task\Factory;

use Api\Service\Task\TaskService;
use Db\Repository\Task\TaskRepositoryInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class TaskServiceFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TaskService
	{
		$taskRepository = $container->get(TaskRepositoryInterface::class);
		return new TaskService($taskRepository);
	}
}