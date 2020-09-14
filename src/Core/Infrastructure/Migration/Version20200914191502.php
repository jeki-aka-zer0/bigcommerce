<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200914191502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE integrations ADD publicApiKey VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE integrations ADD abandonedPeriod INT DEFAULT NULL');
        $this->addSql('ALTER TABLE integrations ADD abandonedUnit VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE integrations DROP publicApiKey');
        $this->addSql('ALTER TABLE integrations DROP abandonedPeriod');
        $this->addSql('ALTER TABLE integrations DROP abandonedUnit');
    }
}
