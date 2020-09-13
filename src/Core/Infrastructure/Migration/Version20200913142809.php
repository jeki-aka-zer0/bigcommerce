<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200913142809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE carts (id VARCHAR(255) NOT NULL, payload JSON NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX cart_id_uidx ON carts (id)');
        $this->addSql('COMMENT ON COLUMN carts.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE jobs (id UUID NOT NULL, subscriberId INT DEFAULT NULL, sign CHAR(255) NOT NULL, scheduled_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX sign_uidx ON jobs (sign)');
        $this->addSql('CREATE INDEX jobs_scheduled_at_idx ON jobs (scheduled_at)');
        $this->addSql('COMMENT ON COLUMN jobs.id IS \'(DC2Type:id)\'');
        $this->addSql('COMMENT ON COLUMN jobs.sign IS \'(DC2Type:sign)\'');
        $this->addSql('COMMENT ON COLUMN jobs.scheduled_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN jobs.create_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE carts');
    }
}
