<?php
namespace Db\Repository\Task;

use Db\Entity\Task\TaskEntityInterface;

interface TaskRepositoryInterface
{
    /**
     * @return TaskEntityInterface[]
     */
    public function findAll(): array;

    public function findById(string $id): ?TaskEntityInterface;

    public function save(TaskEntityInterface $task): void;
}