<?php

declare(strict_types=1);

namespace Db\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425101923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tasks table and enable uuid-ossp extension';
    }

    public function up(Schema $schema): void
    {
	    $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
			
	    $this->addSql('
            CREATE TABLE tasks (
                id UUID NOT NULL DEFAULT uuid_generate_v4(),
                title VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }

    public function down(Schema $schema): void
    {
	    $this->addSql('DROP TABLE IF EXISTS tasks');
	    $this->addSql('DROP EXTENSION IF EXISTS "uuid-ossp"');
    }
}
