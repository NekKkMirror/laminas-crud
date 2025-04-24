<?php
namespace Api\Service\Task\Type;

use Api\Controller\v1\Task\Dto\UpdateTaskDto;
use DateTime;

final class UpdateTaskType
{
	public readonly ?string $title;
	public readonly ?string $description;
	public readonly ?DateTime $createdAt;
	
	private function __construct(?string $title, ?string $description, ?DateTime $createdAt)
	{
		$this->title = $title;
		$this->description = $description;
		$this->createdAt = $createdAt;
	}
	
	public static function fromControllerDto(UpdateTaskDto $dto): self
	{
		return new self(
			$dto->title,
			$dto->description,
			$dto->createdAt
		);
	}
}