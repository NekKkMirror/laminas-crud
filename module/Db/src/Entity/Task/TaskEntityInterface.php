<?php
namespace Db\Entity\Task;

use DateTime;

interface TaskEntityInterface
{
	public function getId(): string;
	public function getTitle(): string;
	public function setTitle(string $title): void;
	public function getDescription(): ?string;
	public function setDescription(?string $description): void;
	public function getCreatedAt(): DateTime;
	public function setCreatedAt(DateTime $createdAt): void;
}