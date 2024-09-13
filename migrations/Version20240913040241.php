<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913040241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paste_user AS SELECT id, user_id FROM paste_user');
        $this->addSql('DROP TABLE paste_user');
        $this->addSql('CREATE TABLE paste_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id BLOB DEFAULT NULL --(DC2Type:uuid)
        , CONSTRAINT FK_2A8ED5E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paste_user (id, user_id) SELECT id, user_id FROM __temp__paste_user');
        $this->addSql('DROP TABLE __temp__paste_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A8ED5E4A76ED395 ON paste_user (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id BLOB NOT NULL --(DC2Type:uuid)
        , username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, username, roles, password) SELECT id, username, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paste_user AS SELECT id, user_id FROM paste_user');
        $this->addSql('DROP TABLE paste_user');
        $this->addSql('CREATE TABLE paste_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, CONSTRAINT FK_2A8ED5E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paste_user (id, user_id) SELECT id, user_id FROM __temp__paste_user');
        $this->addSql('DROP TABLE __temp__paste_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A8ED5E4A76ED395 ON paste_user (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, roles, password) SELECT id, username, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }
}
