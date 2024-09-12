<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911172231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paste AS SELECT id, name, text, expiration_time, access, language, created_at, hash, expiration_date FROM paste');
        $this->addSql('DROP TABLE paste');
        $this->addSql('CREATE TABLE paste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text CLOB NOT NULL, expiration_time INTEGER NOT NULL, access VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , hash VARCHAR(8) NOT NULL, expiration_date DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO paste (id, name, text, expiration_time, access, language, created_at, hash, expiration_date) SELECT id, name, text, expiration_time, access, language, created_at, hash, expiration_date FROM __temp__paste');
        $this->addSql('DROP TABLE __temp__paste');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paste AS SELECT id, name, text, expiration_time, access, language, created_at, expiration_date, hash FROM paste');
        $this->addSql('DROP TABLE paste');
        $this->addSql('CREATE TABLE paste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, text CLOB NOT NULL, expiration_time INTEGER NOT NULL, access VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expiration_date DATETIME NOT NULL, hash VARCHAR(8) NOT NULL)');
        $this->addSql('INSERT INTO paste (id, name, text, expiration_time, access, language, created_at, expiration_date, hash) SELECT id, name, text, expiration_time, access, language, created_at, expiration_date, hash FROM __temp__paste');
        $this->addSql('DROP TABLE __temp__paste');
    }
}
