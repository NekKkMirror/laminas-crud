<?php
namespace Api\Service\Task\Type;

use Db\Entity\Task\TaskEntityInterface;

final class TaskResultType
{
	public readonly string $id;
	public readonly string $title;
	public readonly ?string $description;
	public readonly string $createdAt;
	
	private function __construct(string $id, string $title, ?string $description, string $createdAt)
	{
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->createdAt = $createdAt;
	}
	
	public static function fromEntity(TaskEntityInterface $task): self
	{
		return new self(
			$task->getId(),
			$task->getTitle(),
			$task->getDescription(),
			$task->getCreatedAt()->format('Y-m-d H:i:s')
		);
	}
}