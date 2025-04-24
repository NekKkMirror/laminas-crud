<?php
namespace Db\Entity\Task;

use DateTime;
use Db\Repository\Task\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Db\Doctrine\UuidDefaultGenerator;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: "tasks")]
class TaskEntity implements TaskEntityInterface
{
	#[ORM\Id]
	#[ORM\Column(type: "uuid")]
	#[ORM\GeneratedValue(strategy: "CUSTOM")]
	#[ORM\CustomIdGenerator(class: UuidDefaultGenerator::class)]
	private string $id;
	
	#[ORM\Column(type: "string")]
	private string $title;
	
	#[ORM\Column(type: "text", nullable: true)]
	private ?string $description = null;
	
	#[ORM\Column(type: "datetime")]
	private DateTime $created_at;
	
	public function getId(): string
	{
		return $this->id;
	}
	
	public function getTitle(): string
	{
		return $this->title;
	}
	
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}
	
	public function getDescription(): ?string
	{
		return $this->description;
	}
	
	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}
	
	public function getCreatedAt(): DateTime
	{
		return $this->created_at;
	}
	
	public function setCreatedAt(DateTime $createdAt): void
	{
		$this->created_at = $createdAt;
	}
}