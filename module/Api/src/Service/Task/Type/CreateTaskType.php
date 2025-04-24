<?php
namespace Api\Service\Task\Type;

use Api\Controller\v1\Task\Dto\CreateTaskDto;
use DateTime;

final class CreateTaskType
{
	public readonly string $title;
	public readonly ?string $description;
	public readonly DateTime $createdAt;
	
	private function __construct(string $title, ?string $description, DateTime $createdAt)
	{
		$this->title = $title;
		$this->description = $description;
		$this->createdAt = $createdAt;
	}
	
	public static function fromControllerDto(CreateTaskDto $dto): self
	{
		return new self(
			$dto->title,
			$dto->description,
			$dto->createdAt
		);
	}
}