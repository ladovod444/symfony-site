<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920160802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tags_to_page (page_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(page_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_D5584CFEC4663E4 ON tags_to_page (page_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D5584CFEBAD26311 ON tags_to_page (tag_id)');
        $this->addSql('ALTER TABLE tags_to_page ADD CONSTRAINT FK_D5584CFEC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tags_to_page ADD CONSTRAINT FK_D5584CFEBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
     
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tags_to_page DROP CONSTRAINT FK_D5584CFEC4663E4');
        $this->addSql('ALTER TABLE tags_to_page DROP CONSTRAINT FK_D5584CFEBAD26311');
        $this->addSql('DROP TABLE tags_to_page');
    }
}
