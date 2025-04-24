<?php
namespace Api\Controller\v1\Task;

use Api\Controller\v1\Task\Dto\CreateTaskDto;
use Api\Controller\v1\Task\Dto\TaskResponseDto;
use Api\Controller\v1\Task\Dto\UpdateTaskDto;
use Api\Service\Task\TaskServiceInterface;
use Api\Service\Task\Type\CreateTaskType;
use Api\Service\Task\Type\UpdateTaskType;
use InvalidArgumentException;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     title="Task API",
 *     version="1.0.0",
 *     description="API for managing tasks in Laminas application"
 * )
 * @OA\Tag(
 *     name="Tasks",
 *     description="Operations related to task management"
 * )
 */
class TaskController extends AbstractRestfulController
{
	private TaskServiceInterface $taskService;
	
	protected $identifierName = 'task_id';
	
	public function __construct(TaskServiceInterface $taskService)
	{
		$this->taskService = $taskService;
	}
	
	/**
	 * @OA\Get(
	 *     path="/todo",
	 *     summary="Retrieve a list of all tasks",
	 *     tags={"Tasks"},
	 *     @OA\Response(
	 *         response=200,
	 *         description="Successful operation - list of tasks",
	 *         @OA\JsonContent(
	 *             type="array",
	 *             @OA\Items(ref="#/components/schemas/TaskResponseDto")
	 *         )
	 *     )
	 * )
	 */
	public function getList(): JsonModel
	{
		$tasks = $this->taskService->getList();
		$data = array_map(static fn($task) => TaskResponseDto::fromTaskResult($task)->toArray(), $tasks);

		return new JsonModel($data);
	}
	
	/**
	 * @OA\Get(
	 *     path="/todo/{id}",
	 *     summary="Retrieve a task by its ID",
	 *     tags={"Tasks"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="Task ID (UUID format)",
	 *         @OA\Schema(type="string", format="uuid")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Successful operation - task details",
	 *         @OA\JsonContent(ref="#/components/schemas/TaskResponseDto")
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Task not found",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Task not found")
	 *         )
	 *     )
	 * )
	 */
	public function get($id): JsonModel
	{
		$task = $this->taskService->getById($id);
		if (!$task) {
			$this->getResponse()->setStatusCode(404);
			
			return new JsonModel(['error' => 'Task not found']);
		}
		return new JsonModel(TaskResponseDto::fromTaskResult($task)->toArray());
	}
	
	/**
	 * @OA\Post(
	 *     path="/todo",
	 *     summary="Create a new task",
	 *     tags={"Tasks"},
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Task creation data",
	 *         @OA\JsonContent(ref="#/components/schemas/CreateTaskDto")
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="Task created successfully",
	 *         @OA\JsonContent(ref="#/components/schemas/TaskResponseDto")
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid input data",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Title is required")
	 *         )
	 *     )
	 * )
	 */
	public function create($data): JsonModel
	{
		try {
			$dto = CreateTaskDto::create($data);
			$taskResult = $this->taskService->create(CreateTaskType::fromControllerDto($dto));
		} catch (InvalidArgumentException $e) {
			$this->getResponse()->setStatusCode(400);
			
			return new JsonModel(['error' => $e->getMessage()]);
		}
		
		$this->getResponse()->setStatusCode(201);
		return new JsonModel(TaskResponseDto::fromTaskResult($taskResult)->toArray());
	}
	
	/**
	 * @OA\Put(
	 *     path="/todo/{id}",
	 *     summary="Update an existing task",
	 *     tags={"Tasks"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="Task ID (UUID format)",
	 *         @OA\Schema(type="string", format="uuid")
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Task update data",
	 *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskDto")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Task updated successfully",
	 *         @OA\JsonContent(ref="#/components/schemas/TaskResponseDto")
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Invalid input data",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Invalid created_at format")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Task not found",
	 *         @OA\JsonContent(
	 *             @OA\Property(property="error", type="string", example="Task not found")
	 *         )
	 *     )
	 * )
	 */
	public function update($id, $data): JsonModel
	{
		try {
			$dto = UpdateTaskDto::create($data);
			$taskResult = $this->taskService->update($id, UpdateTaskType::fromControllerDto($dto));
		} catch (InvalidArgumentException $e) {
			$this->getResponse()->setStatusCode(400);
			
			return new JsonModel(['error' => $e->getMessage()]);
		}
		
		if (!$taskResult) {
			$this->getResponse()->setStatusCode(404);
			
			return new JsonModel(['error' => 'Task not found']);
		}
		
		return new JsonModel(TaskResponseDto::fromTaskResult($taskResult)->toArray());
	}
	
}