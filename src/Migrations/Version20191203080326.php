<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191203080326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE instance_api (instance_id INTEGER NOT NULL, api_id INTEGER NOT NULL, PRIMARY KEY(instance_id, api_id))');
        $this->addSql('CREATE INDEX IDX_A5ED8D373A51721D ON instance_api (instance_id)');
        $this->addSql('CREATE INDEX IDX_A5ED8D3754963938 ON instance_api (api_id)');
        $this->addSql('DROP INDEX IDX_C4420F7B54963938');
        $this->addSql('CREATE TEMPORARY TABLE __temp__endpoint AS SELECT id, api_id, name, description FROM endpoint');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('CREATE TABLE endpoint (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, api_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_C4420F7B54963938 FOREIGN KEY (api_id) REFERENCES api (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO endpoint (id, api_id, name, description) SELECT id, api_id, name, description FROM __temp__endpoint');
        $this->addSql('DROP TABLE __temp__endpoint');
        $this->addSql('CREATE INDEX IDX_C4420F7B54963938 ON endpoint (api_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE instance_api');
        $this->addSql('DROP INDEX IDX_C4420F7B54963938');
        $this->addSql('CREATE TEMPORARY TABLE __temp__endpoint AS SELECT id, api_id, name, description FROM endpoint');
        $this->addSql('DROP TABLE endpoint');
        $this->addSql('CREATE TABLE endpoint (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, api_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO endpoint (id, api_id, name, description) SELECT id, api_id, name, description FROM __temp__endpoint');
        $this->addSql('DROP TABLE __temp__endpoint');
        $this->addSql('CREATE INDEX IDX_C4420F7B54963938 ON endpoint (api_id)');
    }
}
