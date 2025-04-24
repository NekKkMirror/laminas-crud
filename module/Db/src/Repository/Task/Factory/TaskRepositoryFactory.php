<?php
namespace Db\Repository\Task\Factory;

use Db\Entity\Task\TaskEntity;
use Db\Repository\Task\TaskRepository;
use Doctrine\ORM\EntityManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class TaskRepositoryFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TaskRepository
	{
		return $container->get(EntityManager::class)->getRepository(TaskEntity::class);
	}
}