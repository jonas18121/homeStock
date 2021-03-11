<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311173906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, lodger_id INT NOT NULL, date_start_at DATETIME NOT NULL, date_created_at DATETIME NOT NULL, INDEX IDX_E00CEDDE36790F15 (lodger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, storage_space_id INT NOT NULL, owner_id INT NOT NULL, content LONGTEXT NOT NULL, date_created_at DATETIME NOT NULL, INDEX IDX_9474526C809C6F07 (storage_space_id), INDEX IDX_9474526C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE storage_space (id INT AUTO_INCREMENT NOT NULL, booking_id INT DEFAULT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, adresse VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, space INT NOT NULL, price INT NOT NULL, date_created_at DATETIME NOT NULL, available TINYINT(1) NOT NULL, images VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_44A6081A3301C60 (booking_id), INDEX IDX_44A6081A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, date_created_at DATETIME NOT NULL, date_update_at DATETIME DEFAULT NULL, images VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE36790F15 FOREIGN KEY (lodger_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C809C6F07 FOREIGN KEY (storage_space_id) REFERENCES storage_space (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE storage_space ADD CONSTRAINT FK_44A6081A3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE storage_space ADD CONSTRAINT FK_44A6081A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storage_space DROP FOREIGN KEY FK_44A6081A3301C60');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C809C6F07');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE36790F15');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7E3C61F9');
        $this->addSql('ALTER TABLE storage_space DROP FOREIGN KEY FK_44A6081A7E3C61F9');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE storage_space');
        $this->addSql('DROP TABLE `user`');
    }
}
