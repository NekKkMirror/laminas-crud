<?php
namespace Db\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Ramsey\Uuid\Uuid;

class UuidDefaultGenerator extends AbstractIdGenerator
{
	public function generateId(EntityManagerInterface $em, ?object $entity): string
	{
		return Uuid::uuid4()->toString();
	}
	
}