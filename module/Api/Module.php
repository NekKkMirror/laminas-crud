<?php
namespace Api;

use Laminas\EventManager\EventInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;

class Module
{
	public function onBootstrap(EventInterface $e): void
	{
		$eventManager = $e->getApplication()->getEventManager();
		$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'handleError']);
		$eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'handleError']);
	}
	
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function handleError(MvcEvent $e): void
	{
		$error = $e->getError();
		if (!$error) {
			return;
		}
		
		$exception = $e->getParam('exception');
		$message = $exception ? $exception->getMessage() : 'An error occurred';
		
		$response = [
			'error'   => true,
			'message' => $message,
		];
		
		if ($exception) {
			$response['details'] = [
				'file'  => $exception->getFile(),
				'line'  => $exception->getLine(),
				'trace' => $exception->getTraceAsString(),
			];
		}
		
		$model = new JsonModel($response);
		
		$e->getViewModel()->setVariables($response);
		$e->setResult($model);
		$e->setViewModel($model);
	}
}