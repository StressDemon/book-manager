<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103013751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author CHANGE website website VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE book CHANGE cover_image cover_image VARCHAR(255) DEFAULT NULL, CHANGE rating_average rating_average NUMERIC(2, 1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBE5A331CC1CF4E6 ON book (isbn)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author CHANGE website website VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX UNIQ_CBE5A331CC1CF4E6 ON book');
        $this->addSql('ALTER TABLE book CHANGE cover_image cover_image VARCHAR(255) DEFAULT \'NULL\', CHANGE rating_average rating_average NUMERIC(2, 1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON `user`');
    }
}
