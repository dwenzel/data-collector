<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191211105029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE instance ADD COLUMN base_url VARCHAR(2048) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_A5ED8D3754963938');
        $this->addSql('DROP INDEX IDX_A5ED8D373A51721D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__instance_api AS SELECT instance_id, api_id FROM instance_api');
        $this->addSql('DROP TABLE instance_api');
        $this->addSql('CREATE TABLE instance_api (instance_id INTEGER NOT NULL, api_id INTEGER NOT NULL, PRIMARY KEY(instance_id, api_id))');
        $this->addSql('INSERT INTO instance_api (instance_id, api_id) SELECT instance_id, api_id FROM __temp__instance_api');
        $this->addSql('DROP TABLE __temp__instance_api');
        $this->addSql('DROP INDEX IDX_C4420F7B54963938');
        $this->addSql('CREATE TEMPORARY TABLE __temp__endpoint AS SELECT id, api_id, name, description FROM endpoint');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('CREATE TABLE endpoint (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, api_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO endpoint (id, api_id, name, description) SELECT id, api_id, name, description FROM __temp__endpoint');
        $this->addSql('DROP TABLE __temp__endpoint');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__endpoint AS SELECT id, name, description, api_id FROM endpoint');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('CREATE TABLE endpoint (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, api_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO endpoint (id, name, description, api_id) SELECT id, name, description, api_id FROM __temp__endpoint');
        $this->addSql('DROP TABLE __temp__endpoint');
        $this->addSql('CREATE INDEX IDX_C4420F7B54963938 ON endpoint (api_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__instance AS SELECT id, name, uuid, role FROM instance');
        $this->addSql('DROP TABLE instance');
        $this->addSql('CREATE TABLE instance (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL --(DC2Type:guid)
        , role VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO instance (id, name, uuid, role) SELECT id, name, uuid, role FROM __temp__instance');
        $this->addSql('DROP TABLE __temp__instance');
        $this->addSql('CREATE TEMPORARY TABLE __temp__instance_api AS SELECT instance_id, api_id FROM instance_api');
        $this->addSql('DROP TABLE instance_api');
        $this->addSql('CREATE TABLE instance_api (instance_id INTEGER NOT NULL, api_id INTEGER NOT NULL, PRIMARY KEY(instance_id, api_id))');
        $this->addSql('INSERT INTO instance_api (instance_id, api_id) SELECT instance_id, api_id FROM __temp__instance_api');
        $this->addSql('DROP TABLE __temp__instance_api');
        $this->addSql('CREATE INDEX IDX_A5ED8D3754963938 ON instance_api (api_id)');
        $this->addSql('CREATE INDEX IDX_A5ED8D373A51721D ON instance_api (instance_id)');
    }
}
