<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216173757 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE games (id UUID NOT NULL, player_id UUID NOT NULL, level CHAR(4) NOT NULL, figures CHAR(4) NOT NULL, result BOOLEAN DEFAULT NULL, moves_count SMALLINT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FF232B3199E6F5DF ON games (player_id)');
        $this->addSql('COMMENT ON COLUMN games.id IS \'(DC2Type:id)\'');
        $this->addSql('COMMENT ON COLUMN games.player_id IS \'(DC2Type:id)\'');
        $this->addSql('COMMENT ON COLUMN games.level IS \'(DC2Type:level)\'');
        $this->addSql('COMMENT ON COLUMN games.figures IS \'(DC2Type:figures)\'');
        $this->addSql('COMMENT ON COLUMN games.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B3199E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE games');
    }
}
