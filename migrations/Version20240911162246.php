<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911162246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paste AS SELECT id, name, text, expiration_time, access, language, created_at FROM paste');
        $this->addSql('DROP TABLE paste');
        $this->addSql('CREATE TABLE paste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text CLOB NOT NULL, expiration_time INTEGER NOT NULL, access VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , hash VARCHAR(8) NOT NULL)');
        $this->addSql('INSERT INTO paste (id, name, text, expiration_time, access, language, created_at) SELECT id, name, text, expiration_time, access, language, created_at FROM __temp__paste');
        $this->addSql('DROP TABLE __temp__paste');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paste AS SELECT id, name, text, expiration_time, access, language, created_at FROM paste');
        $this->addSql('DROP TABLE paste');
        $this->addSql('CREATE TABLE paste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text CLOB NOT NULL, expiration_time INTEGER NOT NULL, access VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO paste (id, name, text, expiration_time, access, language, created_at) SELECT id, name, text, expiration_time, access, language, created_at FROM __temp__paste');
        $this->addSql('DROP TABLE __temp__paste');
    }
}
