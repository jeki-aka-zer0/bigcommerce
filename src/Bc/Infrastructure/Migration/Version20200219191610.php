<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219191610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE moves (id UUID NOT NULL, game_id UUID NOT NULL, figures CHAR(4) NOT NULL, bulls SMALLINT NOT NULL, cows SMALLINT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_453F0832E48FD905 ON moves (game_id)');
        $this->addSql('COMMENT ON COLUMN moves.id IS \'(DC2Type:id)\'');
        $this->addSql('COMMENT ON COLUMN moves.game_id IS \'(DC2Type:id)\'');
        $this->addSql('COMMENT ON COLUMN moves.figures IS \'(DC2Type:figures)\'');
        $this->addSql('COMMENT ON COLUMN moves.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE moves ADD CONSTRAINT FK_453F0832E48FD905 FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE moves');
    }
}
