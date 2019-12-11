<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191211111931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__instance AS SELECT id, uuid, name, role, base_url FROM instance');
        $this->addSql('DROP TABLE instance');
        $this->addSql('CREATE TABLE instance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, role VARCHAR(255) DEFAULT NULL COLLATE BINARY, base_url VARCHAR(2048) NOT NULL)');
        $this->addSql('INSERT INTO instance (id, uuid, name, role, base_url) SELECT id, uuid, name, role, base_url FROM __temp__instance');
        $this->addSql('DROP TABLE __temp__instance');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__instance AS SELECT id, name, uuid, role, base_url FROM instance');
        $this->addSql('DROP TABLE instance');
        $this->addSql('CREATE TABLE instance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL --(DC2Type:guid)
        , role VARCHAR(255) DEFAULT NULL, base_url VARCHAR(2048) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO instance (id, name, uuid, role, base_url) SELECT id, name, uuid, role, base_url FROM __temp__instance');
        $this->addSql('DROP TABLE __temp__instance');
    }
}
