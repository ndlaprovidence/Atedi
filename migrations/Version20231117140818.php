<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117140818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_intervention ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_intervention ADD CONSTRAINT FK_2F4B6E3DA76ED395 FOREIGN KEY (user_id) REFERENCES tbl_user (id)');
        $this->addSql('CREATE INDEX IDX_2F4B6E3DA76ED395 ON tbl_intervention (user_id)');
        $this->addSql('ALTER TABLE tbl_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_intervention DROP FOREIGN KEY FK_2F4B6E3DA76ED395');
        $this->addSql('DROP INDEX IDX_2F4B6E3DA76ED395 ON tbl_intervention');
        $this->addSql('ALTER TABLE tbl_intervention DROP user_id');
        $this->addSql('ALTER TABLE tbl_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
