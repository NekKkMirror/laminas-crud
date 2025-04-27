<?php
namespace ApiTest\Mock\Task;

use InvalidArgumentException;

class TaskMockData
{
	private const DATA = [
		'task0' => [
			'title' => 'Mock Task 1',
			'description' => 'This is the first mock task for testing',
			'created_at' => '2021-01-01 00:00:00',
		],
		'task1' => [
			'title' => 'Mock Task 2',
			'description' => 'This is the second mock task for testing',
			'created_at' => '2021-01-02 00:00:00'
		],
	];
	
	public static function getTask(string $key): array
	{
		return self::DATA[$key] ?? throw new InvalidArgumentException("Mock task '$key' not found");
	}
	
	public static function getAllTasks(): array
	{
		return self::DATA;
	}
}