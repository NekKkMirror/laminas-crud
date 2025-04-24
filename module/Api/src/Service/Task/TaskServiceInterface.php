<?php
namespace Api\Service\Task;

use Api\Service\Task\Type\CreateTaskType;
use Api\Service\Task\Type\TaskResultType;
use Api\Service\Task\Type\UpdateTaskType;

interface TaskServiceInterface
{
	/**
	 * @return TaskResultType[]
	 */
	public function getList(): array;
	
	public function getById(string $id): ?TaskResultType;
	
	public function create(CreateTaskType $type): TaskResultType;
	
	public function update(string $id, UpdateTaskType $type): ?TaskResultType;
}