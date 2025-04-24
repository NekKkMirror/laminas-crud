<?php
namespace Api\Controller\v1\Task\Dto;

use DateTime;
use InvalidArgumentException;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTaskDto",
 *     type="object",
 *     title="Update Task DTO",
 *     description="Data structure for updating an existing task",
 *     @OA\Property(property="title", type="string", description="Task title", example="Updated Task"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Task description", example="Updated details"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date", example="2023-10-15 12:00:00")
 * )
 */
final class UpdateTaskDto
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
	
	public static function create(array $data): self
	{
		empty($data) && throw new InvalidArgumentException('Data is required');
		
		if (empty($data['title'])) {
			throw new InvalidArgumentException("Title can't be null");
		}
		
		$createdAtDate = null;
		if (isset($data['created_at']) && $data['created_at'] !== '') {
			try {
				$createdAtDate = new DateTime($data['created_at']);
			} catch (\Exception $e) {
				throw new InvalidArgumentException('Invalid created_at format');
			}
		}
		
		return new self(
			$data['title'],
			$data['description'] ?? null,
			$createdAtDate
		);
	}
}