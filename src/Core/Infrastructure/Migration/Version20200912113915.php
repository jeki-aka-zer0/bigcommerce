<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200912113915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE stores (id VARCHAR(255) NOT NULL, integration_id UUID NOT NULL, payload JSON NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D5907CCC9E82DDEA ON stores (integration_id)');
        $this->addSql('CREATE INDEX store_id_uidx ON stores (id)');
        $this->addSql('COMMENT ON COLUMN stores.integration_id IS \'(DC2Type:id)\'');
        $this->addSql('COMMENT ON COLUMN stores.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE stores ADD CONSTRAINT FK_D5907CCC9E82DDEA FOREIGN KEY (integration_id) REFERENCES integrations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE stores');
    }
}
