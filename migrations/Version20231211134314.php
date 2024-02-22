<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211134314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention_props RENAME INDEX intervention_id TO IDX_2E1BB62C8EAE3863');
        $this->addSql('ALTER TABLE intervention_props RENAME INDEX task_id TO IDX_2E1BB62C527FC1EB');
        $this->addSql('ALTER TABLE tbl_props CHANGE title title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervention_props RENAME INDEX idx_2e1bb62c527fc1eb TO task_id');
        $this->addSql('ALTER TABLE intervention_props RENAME INDEX idx_2e1bb62c8eae3863 TO intervention_id');
        $this->addSql('ALTER TABLE tbl_props CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
