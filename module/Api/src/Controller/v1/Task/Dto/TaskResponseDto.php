<?php
namespace Api\Controller\v1\Task\Dto;

use Api\Service\Task\Type\TaskResultType;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TaskResponseDto",
 *     type="object",
 *     title="Task Response DTO",
 *     description="Data structure for task response",
 *     required={"id", "title", "created_at"},
 *     @OA\Property(property="id", type="string", format="uuid", description="Task ID", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="title", type="string", description="Task title", example="Complete project"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Task description", example="Finish the API documentation"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date", example="2023-10-15 12:00:00")
 * )
 */
final class TaskResponseDto
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
	
	public static function fromTaskResult(TaskResultType $taskResult): self
	{
		return new self(
			$taskResult->id,
			$taskResult->title,
			$taskResult->description,
			$taskResult->createdAt
		);
	}
	
	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'created_at' => $this->createdAt,
		];
	}
}