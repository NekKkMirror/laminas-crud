<?php
namespace Db;

use Db\Repository\Task\Factory\TaskRepositoryFactory;
use Db\Repository\Task\TaskRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;

return [
	
	'doctrine' => [
		'connection' => [
			'orm_default' => [
				'driver' => 'pdo_pgsql',
				'host' => getenv('DB_HOST'),
				'port' => getenv('DB_PORT'),
				'user' => getenv('DB_USER'),
				'password' => getenv('DB_PASSWORD'),
				'dbname' => getenv('DB_NAME'),
				'unix_socket' => null,
			],
		],
		'driver' => [
			'Db_driver' => [
				'class' => AttributeDriver::class,
				'paths' => [__DIR__ . '/../src/Entity'],
			],
			'orm_default' => [
				'drivers' => [
					'Db\Entity' => 'Db_driver',
				],
			],
		],
	],
	
	'service_manager' => [
		'factories' => [
			EntityManager::class => static function ($container) {
				if (!Type::hasType('uuid')) {
					Type::addType('uuid', GuidType::class);
				}
	
				$settings = $container->get('config')['doctrine']['connection']['orm_default'];
				$config = ORMSetup::createAttributeMetadataConfiguration(
					paths: [__DIR__ . '/../src/Entity'],
					isDevMode: getenv('APP_NODE_ENV') === 'development'
				);
				
				$connection = DriverManager::getConnection($settings);
				$connection->getDatabasePlatform()->registerDoctrineTypeMapping('uuid', 'uuid');
				
				return new EntityManager($connection, $config);
			},
			DependencyFactory::class => static function ($container) {
				$migrationConfig = include __DIR__ . '/doctrine_migrations.php';
				$entityManager = $container->get(EntityManager::class);
				
				return DependencyFactory::fromEntityManager(
					new ConfigurationArray($migrationConfig),
					new ExistingEntityManager($entityManager)
				);
			},
			TaskRepository::class => TaskRepositoryFactory::class
		],
	],
];