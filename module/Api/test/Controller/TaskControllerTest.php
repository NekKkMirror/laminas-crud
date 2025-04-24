<?php
namespace ApiTest\Controller;

use Api\Controller\v1\Task\TaskController;
use Api\Entity\Task;
use Laminas\View\Model\JsonModel;
use PHPUnit\Framework\TestCase;

class TaskControllerTest extends TestCase
{
	protected $entityManager;
	protected $repository;
	
	public function setUp(): void
	{
		// Мокаем репозиторий для задачи
		$this->repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
			->disableOriginalConstructor()
			->getMock();
		
		// Мокаем EntityManager и его метод getRepository
		$this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
			->disableOriginalConstructor()
			->getMock();
		
		$this->entityManager->method('getRepository')->willReturn($this->repository);
	}
	
	public function testGetList()
	{
		$task = new Task();
		$task->setTitle("Отчет для Джона.");
		$task->setDescription("Описание задачи");
		$task->setCreatedAt(new \DateTime("2017-03-05 12:00:00"));
		
		$this->repository->expects($this->once())
			->method('findAll')
			->willReturn([$task]);
		
		$controller = new TaskController($this->entityManager);
		$result = $controller->getList();
		
		$this->assertInstanceOf(JsonModel::class, $result);
		$data = $result->getVariables();
		$this->assertCount(1, $data);
		$this->assertEquals("Отчет для Джона.", $data[0]['title']);
	}
	
	public function testGetTaskNotFound()
	{
		$this->repository->expects($this->once())
			->method('find')
			->with(99)
			->willReturn(null);
		
		$controller = new TaskController($this->entityManager);
		$result = $controller->get(99);
		
		$data = $result->getVariables();
		$this->assertArrayHasKey('error', $data);
	}
	
	// Дополнительно можно написать тесты для create и update методов с учётом валидации
}