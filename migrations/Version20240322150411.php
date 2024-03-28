<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322150411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervention_prop (intervention_id INT NOT NULL, prop_id INT NOT NULL, INDEX IDX_A0D1DD288EAE3863 (intervention_id), INDEX IDX_A0D1DD28DEB3FFBD (prop_id), PRIMARY KEY(intervention_id, prop_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_prop (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervention_prop ADD CONSTRAINT FK_A0D1DD288EAE3863 FOREIGN KEY (intervention_id) REFERENCES tbl_intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_prop ADD CONSTRAINT FK_A0D1DD28DEB3FFBD FOREIGN KEY (prop_id) REFERENCES tbl_prop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_props DROP FOREIGN KEY FK_2E1BB62C527FC1EB');
        $this->addSql('ALTER TABLE intervention_props DROP FOREIGN KEY FK_2E1BB62C8EAE3863');
        $this->addSql('DROP TABLE intervention_props');
        $this->addSql('DROP TABLE tbl_props');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intervention_props (intervention_id INT NOT NULL, props_id INT NOT NULL, INDEX IDX_2E1BB62C527FC1EB (props_id), INDEX IDX_2E1BB62C8EAE3863 (intervention_id), PRIMARY KEY(intervention_id, props_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tbl_props (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE intervention_props ADD CONSTRAINT FK_2E1BB62C527FC1EB FOREIGN KEY (props_id) REFERENCES tbl_props (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_props ADD CONSTRAINT FK_2E1BB62C8EAE3863 FOREIGN KEY (intervention_id) REFERENCES tbl_intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_prop DROP FOREIGN KEY FK_A0D1DD288EAE3863');
        $this->addSql('ALTER TABLE intervention_prop DROP FOREIGN KEY FK_A0D1DD28DEB3FFBD');
        $this->addSql('DROP TABLE intervention_prop');
        $this->addSql('DROP TABLE tbl_prop');
    }
}
