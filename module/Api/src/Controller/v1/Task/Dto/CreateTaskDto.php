<?php
namespace Api\Controller\v1\Task\Dto;

use DateTime;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CreateTaskDto",
 *     type="object",
 *     title="Create Task DTO",
 *     description="Data structure for creating a new task",
 *     required={"title", "created_at"},
 *     @OA\Property(property="title", type="string", description="Task title", example="New Task"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Task description", example="Task details"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date", example="2023-10-15 2:00:00")
 * )
 */
final class CreateTaskDto
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
	
	public static function create(array $data): self
	{
		$title = $data['title'] ?? '';
		if (empty($title)) {
			throw new InvalidArgumentException('Title is required');
		}
		
		$createdAt = $data['created_at'] ?? '';
		if (empty($createdAt)) {
			throw new InvalidArgumentException('Created_at is required');
		}
		
		try {
			$createdAtDate = new DateTime($createdAt);
		} catch (\Exception $e) {
			throw new InvalidArgumentException('Invalid created_at format');
		}
		
		return new self(
			$title,
			$data['description'],
			$createdAtDate
		);
	}
}