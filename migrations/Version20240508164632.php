<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508134632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds posts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE 
                posts (
                    id SERIAL PRIMARY KEY, 
                    title TEXT, 
                    content TEXT, 
                    slug VARCHAR(100) UNIQUE,
                    author_id INT REFERENCES users(id) ON DELETE CASCADE
                      )
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE posts');
    }
}
