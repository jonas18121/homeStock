<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316170723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD storage_space_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE809C6F07 FOREIGN KEY (storage_space_id) REFERENCES storage_space (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE809C6F07 ON booking (storage_space_id)');
        $this->addSql('ALTER TABLE storage_space DROP FOREIGN KEY FK_44A6081A3301C60');
        $this->addSql('DROP INDEX UNIQ_44A6081A3301C60 ON storage_space');
        $this->addSql('ALTER TABLE storage_space DROP booking_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE809C6F07');
        $this->addSql('DROP INDEX IDX_E00CEDDE809C6F07 ON booking');
        $this->addSql('ALTER TABLE booking DROP storage_space_id');
        $this->addSql('ALTER TABLE storage_space ADD booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE storage_space ADD CONSTRAINT FK_44A6081A3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_44A6081A3301C60 ON storage_space (booking_id)');
    }
}
