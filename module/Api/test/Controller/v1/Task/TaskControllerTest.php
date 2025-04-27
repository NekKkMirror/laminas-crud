<?php
namespace ApiTest\Controller\v1\Task;

use Api\Controller\v1\Task\TaskController;
use ApiTest\Mock\Task\TaskMockData;
use Exception;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Ramsey\Uuid\Uuid;

class TaskControllerTest extends AbstractHttpControllerTestCase
{
	
	use TaskTestTrait;
	
	protected function setUp(): void
	{
		$this->setApplicationConfig(
			require __DIR__ . '/../../../../../../config/application.config.php'
		);
		parent::setUp();
		$this->configureEntityManager();
	}
	
	/**
	 * @covers \Api\Controller\v1\Task\TaskController::getList
	 * @throws Exception
	 */
	public function testGetListReturnsEmptyArrayWhenNoTasks(): void
	{
		$this->dispatchJson('/api/v1/todo', 'GET');
		
		$this->assertResponseOk();
		$this->assertJsonResponse([]);
	}
	
	/**
	 * @covers \Api\Controller\v1\Task\TaskController::getList
	 * @throws Exception
	 */
	public function testGetListReturnsTasksWhenTasksExist(): void
	{
		$tasks = $this->createTasksFromMockData(['task0', 'task1']);

		$this->dispatchJson('/api/v1/todo', 'GET');
		$this->assertResponseOk();
		
		$content = $this->getJsonResponse();
		$this->assertCount(2, $content);
		
		foreach ($tasks as $index => $task) {
			$mockData = TaskMockData::getTask("task{$index}");
	
			$this->assertTaskMatchesData($content[$index], $task, $mockData);
		}
	}
	
	/**
	 * @covers \Api\Controller\v1\Task\TaskController::get
	 * @throws Exception
	 */
	public function testGetTaskReturnsTaskWhenIdExists(): void
	{
		$task = $this->createTaskFromMockData('task0');

		$this->dispatchJson('/api/v1/todo/' . $task->getId() , 'GET');
		$this->assertResponseOk();

		$content = $this->getJsonResponse();
		
		$this->assertTaskMatchesData($content, $task, TaskMockData::getTask('task0'));
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::get
	 * @throws Exception
	 */
	public function testGetTaskReturns404WhenIdDoesNotExist(): void
	{
		$this->dispatchJson('/api/v1/todo/' . Uuid::uuid4()->toString(), 'GET');
		$this->assertResponseNotFound();
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::get
	 * @throws Exception
	 */
	public function testGetTaskReturns404WhenIdIsInvalidUuid(): void
	{
		$this->dispatchJson('/api/v1/todo/' . 'invalid-uuid', 'GET');
		$this->assertResponseNotFound();
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::create
	 * @throws Exception
	 */
	public function testCreateTaskSucceedsWithValidData(): void
	{
		$taskData = TaskMockData::getTask('task0');
		
		$this->dispatchJson('/api/v1/todo', 'POST', $taskData);
		$this->assertResponseCreated();
		
		$content = $this->getJsonResponse();
		
		$this->assertTrue(Uuid::isValid($content['id']));
		$this->assertEquals($taskData['title'], $content['title']);
		$this->assertEquals($taskData['description'], $content['description']);
		$this->assertEquals($taskData['created_at'], $content['created_at']);
		
		$task = $this->findTask($content['id']);
		
		$this->assertNotNull($task);
		$this->assertTaskMatchesData($content, $task, $taskData);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::create
	 * @throws Exception
	 */
	public function testCreateTaskReturns400WhenTitleIsMissing(): void
	{
		$this->dispatchJson('/api/v1/todo', 'POST', ['title' => '']);
		$this->assertResponseBadRequest(['error' => 'Title is required']);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::create
	 * @throws Exception
	 */
	public function testCreateTaskReturns400WhenCreatedAtIsInvalid(): void
	{
		$taskData = TaskMockData::getTask('task0');
		$taskData['created_at'] = 'invalid-date';
		
		$this->dispatchJson('/api/v1/todo', 'POST', $taskData);
		$this->assertResponseBadRequest(['error' => 'Invalid created_at format']);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::create
	 * @throws Exception
	 */
	public function testCreateTaskReturns400WhenCreatedAtIsMissing(): void
	{
		$taskData = TaskMockData::getTask('task0');
		unset($taskData['created_at']);
		
		$this->dispatchJson('/api/v1/todo', 'POST', $taskData);
		$this->assertResponseBadRequest(['error' => 'Created_at is required']);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskSucceedsWithValidData(): void
	{
		$task = $this->createTaskFromMockData('task0');
		$updateData = [
			'title' => 'Updated Task',
			'description' => 'Updated Description',
			'created_at' => '2023-10-15 12:00:00',
		];
		
		$this->dispatchJson("/api/v1/todo/{$task->getId()}", 'PUT', $updateData);
		$this->assertResponseOk();
		
		$content = $this->getJsonResponse();
		
		$this->assertEquals($task->getId(), $content['id']);
		$this->assertEquals($updateData['title'], $content['title']);
		$this->assertEquals($updateData['description'], $content['description']);
		$this->assertEquals($updateData['created_at'], $content['created_at']);
		
		$updatedTask = $this->findTask($task->getId());
		
		$this->assertTaskMatchesData($content, $updatedTask, $updateData);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskReturns404WhenIdDoesNotExist(): void
	{
		$this->dispatchJson('/api/v1/todo/' . Uuid::uuid4()->toString(), 'PUT', TaskMockData::getTask('task0'));
		$this->assertResponseNotFound();
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskReturns400WhenTitleIsMissing(): void
	{
		$task = $this->createTaskFromMockData('task0');
		$updateData = [
			'title' => '',
			'description' => 'Updated Description',
			'created_at' => '2023-10-15 12:00:00',
		];
		
		$this->dispatchJson("/api/v1/todo/{$task->getId()}", 'PUT', $updateData);
		$this->assertResponseBadRequest(['error' => "Title can't be null"]);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskReturns400WhenCreatedAtIsInvalid(): void
	{
		$task = $this->createTaskFromMockData('task0');
		$updateData = [
			'title' => 'Updated Task',
			'description' => 'Updated Description',
			'created_at' => 'invalid-date',
		];
		
		$this->dispatchJson("/api/v1/todo/{$task->getId()}", 'PUT', $updateData);
		$this->assertResponseBadRequest(['error' => 'Invalid created_at format']);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskReturns400WhenDataIsEmpty(): void
	{
		$task = $this->createTaskFromMockData('task0');
		
		$this->dispatchJson("/api/v1/todo/{$task->getId()}", 'PUT', []);
		$this->assertResponseBadRequest(['error' => 'Data is required']);
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskReturns404WhenIdIsInvalidUuid(): void
	{
		$this->dispatchJson('/api/v1/todo/invalid-uuid', 'PUT', TaskMockData::getTask('task0'));
		$this->assertResponseNotFound();
	}

	/**
	 * @covers \Api\Controller\v1\Task\TaskController::update
	 * @throws Exception
	 */
	public function testUpdateTaskSucceedsWithPartialData(): void
	{
		$task = $this->createTaskFromMockData('task0');
		$updateData = ['title' => 'Updated Task'];
		
		$this->dispatchJson("/api/v1/todo/{$task->getId()}", 'PUT', $updateData);
		$this->assertResponseOk();
		
		$content = $this->getJsonResponse();
		
		$this->assertEquals($task->getId(), $content['id']);
		$this->assertEquals($updateData['title'], $content['title']);
		$this->assertEquals(TaskMockData::getTask('task0')['description'], $content['description']);
		$this->assertEquals(TaskMockData::getTask('task0')['created_at'], $content['created_at']);
	}
}