<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221022083831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE movie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE movie (id INT NOT NULL, kp_id INT NOT NULL, name VARCHAR(150) NOT NULL, description TEXT NOT NULL, year INT DEFAULT NULL, rating_kp DOUBLE PRECISION DEFAULT NULL, rating_imdb DOUBLE PRECISION DEFAULT NULL, poster_url VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE movie_user (movie_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(movie_id, user_id))');
        $this->addSql('CREATE INDEX IDX_7EF5F7448F93B6FC ON movie_user (movie_id)');
        $this->addSql('CREATE INDEX IDX_7EF5F744A76ED395 ON movie_user (user_id)');
        $this->addSql('ALTER TABLE movie_user ADD CONSTRAINT FK_7EF5F7448F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_user ADD CONSTRAINT FK_7EF5F744A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE movie_id_seq CASCADE');
        $this->addSql('ALTER TABLE movie_user DROP CONSTRAINT FK_7EF5F7448F93B6FC');
        $this->addSql('ALTER TABLE movie_user DROP CONSTRAINT FK_7EF5F744A76ED395');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_user');
    }
}
