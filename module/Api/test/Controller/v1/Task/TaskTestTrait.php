<?php
namespace ApiTest\Controller\v1\Task;

use ApiTest\Mock\Task\TaskMockData;
use DateTime;
use Db\Entity\Task\TaskEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use const ApiTest\Constant\API_JSON_CONTENT_TYPE_HEADER_VALUE;

trait TaskTestTrait
{
	protected function configureEntityManager(): void
	{
		$entityManager = $this->getApplicationServiceLocator()->get(EntityManager::class);
		$schemaTool = new SchemaTool($entityManager);
		$metadata = $entityManager->getMetadataFactory()->getAllMetadata();
		$schemaTool->dropSchema($metadata);
		$schemaTool->createSchema($metadata);
	}
	
	protected function createTask(string $title, ?string $description, DateTime $createdAt): TaskEntity
	{
		$task = new TaskEntity();
		$task->setTitle($title);
		$task->setDescription($description);
		$task->setCreatedAt($createdAt);
		
		$entityManager = $this->getApplicationServiceLocator()->get(EntityManager::class);
		$entityManager->persist($task);
		$entityManager->flush();
		
		return $task;
	}
	
	protected function createTaskFromMockData(string $taskKey): TaskEntity
	{
		$taskData = TaskMockData::getTask($taskKey);
		return $this->createTask(
			$taskData['title'],
			$taskData['description'],
			new DateTime($taskData['created_at'])
		);
	}
	
	protected function createTasksFromMockData(array $taskKeys): array
	{
		return array_map(function ($key) {
			return $this->createTaskFromMockData($key);
		}, $taskKeys);
	}
	
	protected function findTask(string $id): ?TaskEntity
	{
		$entityManager = $this->getApplicationServiceLocator()->get(EntityManager::class);
		return $entityManager->find(TaskEntity::class, $id);
	}
	
	protected function dispatchJson(string $url, string $method, array $data = []): void
	{
		$this->getRequest()
			->setContent(json_encode($data))
			->getHeaders()
			->addHeaderLine('Content-Type', 'application/json');
		$this->dispatch($url, $method);
	}
	
	protected function assertResponseOk(): void
	{
		$this->assertResponseStatusCode(200);
		$this->assertResponseHeaderContains('Content-Type', API_JSON_CONTENT_TYPE_HEADER_VALUE);
	}
	
	protected function assertResponseCreated(): void
	{
		$this->assertResponseStatusCode(201);
		$this->assertResponseHeaderContains('Content-Type', API_JSON_CONTENT_TYPE_HEADER_VALUE);
	}
	
	protected function assertResponseBadRequest(array $expectedContent): void
	{
		$this->assertResponseStatusCode(400);
		$this->assertResponseHeaderContains('Content-Type', API_JSON_CONTENT_TYPE_HEADER_VALUE);
		$this->assertJsonResponse($expectedContent);
	}
	
	protected function assertResponseNotFound(?array $expectedContent = null): void
	{
		$this->assertResponseStatusCode(404);
		$this->assertResponseHeaderContains('Content-Type', API_JSON_CONTENT_TYPE_HEADER_VALUE);
		if ($expectedContent) {
			$this->assertJsonResponse($expectedContent);
		}
	}
	
	protected function getJsonResponse(): array
	{
		return json_decode($this->getResponse()->getContent(), true);
	}
	
	protected function assertJsonResponse(array $expected): void
	{
		$this->assertEquals($expected, $this->getJsonResponse());
	}
	
	protected function assertTaskMatchesData(array $content, TaskEntity $task, array $taskData): void
	{
		$this->assertEquals($task->getId(), $content['id']);
		$this->assertEquals($taskData['title'], $content['title']);
		$this->assertEquals($taskData['description'], $content['description']);
		$this->assertEquals($taskData['created_at'], $content['created_at']);
	}
}