<?php
namespace Api\Service\Task;

use Api\Service\Task\Type\CreateTaskType;
use Api\Service\Task\Type\TaskResultType;
use Api\Service\Task\Type\UpdateTaskType;
use Db\Entity\Task\TaskEntity;
use Db\Repository\Task\TaskRepositoryInterface;

class TaskService implements TaskServiceInterface
{
	private TaskRepositoryInterface $taskRepository;
	
	public function __construct(TaskRepositoryInterface $taskRepository)
	{
		$this->taskRepository = $taskRepository;
	}
	
	/**
	 * @return TaskResultType[]
	 */
	public function getList(): array
	{
		$tasks = $this->taskRepository->findAll();
		
		return array_map([TaskResultType::class, 'fromEntity'], $tasks);
	}
	
	public function getById(string $id): ?TaskResultType
	{
		$task = $this->taskRepository->findById($id);
		
		return $task ? TaskResultType::fromEntity($task) : null;
	}
	
	public function create(CreateTaskType $type): TaskResultType
	{
		$task = new TaskEntity();
		$task->setTitle($type->title);
		$task->setDescription($type->description);
		$task->setCreatedAt($type->createdAt);
		
		$this->taskRepository->save($task);
		
		return TaskResultType::fromEntity($task);
	}
	
	public function update(string $id, UpdateTaskType $type): ?TaskResultType
	{
		$task = $this->taskRepository->findById($id);
		if (!$task) {
			return null;
		}
		
		if ($type->title !== null) {
			$task->setTitle($type->title);
		}
		
		if ($type->description !== null) {
			$task->setDescription($type->description);
		}
		
		if ($type->createdAt !== null) {
			$task->setCreatedAt($type->createdAt);
		}
		
		$this->taskRepository->save($task);
		
		return TaskResultType::fromEntity($task);
	}
}