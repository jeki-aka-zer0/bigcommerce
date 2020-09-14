<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200914171133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX store_hash_uidx');
        $this->addSql('ALTER TABLE integrations ALTER storehash TYPE VARCHAR(255)');
        $this->addSql('CREATE UNIQUE INDEX store_hash_uidx ON integrations (storeHash)');
        $this->addSql('ALTER TABLE jobs ALTER sign TYPE VARCHAR(255)');
        $this->addSql('DROP INDEX cart_id_uidx');
        $this->addSql('CREATE UNIQUE INDEX cart_id_uidx ON carts (id)');
        $this->addSql('DROP INDEX store_id_uidx');
        $this->addSql('CREATE UNIQUE INDEX store_id_uidx ON stores (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX store_hash_uidx');
        $this->addSql('ALTER TABLE integrations ALTER storeHash TYPE CHAR(255)');
        $this->addSql('CREATE INDEX store_hash_uidx ON integrations (storehash)');
        $this->addSql('DROP INDEX store_id_uidx');
        $this->addSql('CREATE INDEX store_id_uidx ON stores (id)');
        $this->addSql('DROP INDEX cart_id_uidx');
        $this->addSql('CREATE INDEX cart_id_uidx ON carts (id)');
        $this->addSql('ALTER TABLE jobs ALTER sign TYPE CHAR(255)');
    }
}
