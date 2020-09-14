<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200914195504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE jobs ADD integration_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN jobs.integration_id IS \'(DC2Type:id)\'');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC59E82DDEA FOREIGN KEY (integration_id) REFERENCES integrations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A8936DC59E82DDEA ON jobs (integration_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE jobs DROP CONSTRAINT FK_A8936DC59E82DDEA');
        $this->addSql('DROP INDEX IDX_A8936DC59E82DDEA');
        $this->addSql('ALTER TABLE jobs DROP integration_id');
    }
}
