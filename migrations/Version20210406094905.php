<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406094905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE storage_space ADD category_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE storage_space ADD CONSTRAINT FK_44A6081A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_44A6081A12469DE2 ON storage_space (category_id)');
        $this->addSql('ALTER TABLE user ADD customer_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE storage_space DROP FOREIGN KEY FK_44A6081A12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_44A6081A12469DE2 ON storage_space');
        $this->addSql('ALTER TABLE storage_space ADD type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP category_id');
        $this->addSql('ALTER TABLE `user` DROP customer_id');
    }
}
