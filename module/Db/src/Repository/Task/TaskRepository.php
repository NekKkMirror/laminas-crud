<?php
namespace Db\Repository\Task;

use Db\Entity\Task\TaskEntityInterface;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository implements TaskRepositoryInterface
{
	/**
	 * @return TaskEntityInterface[]
	 */
	public function findAll(): array
	{
		return parent::findAll();
	}
	
	public function findById(string $id): ?TaskEntityInterface
	{
		return $this->find($id);
	}
	
	public function save(TaskEntityInterface $task): void
	{
		$this->getEntityManager()->persist($task);
		$this->getEntityManager()->flush();
	}
}