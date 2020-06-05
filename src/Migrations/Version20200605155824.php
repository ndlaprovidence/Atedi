<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605155824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, operating_system_id INT NOT NULL, equipment_id INT NOT NULL, client_id INT NOT NULL, intervention_report_id INT NOT NULL, deposit_date DATETIME NOT NULL, return_date DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, equipment_complete VARCHAR(255) NOT NULL, INDEX IDX_D11814ABA391D4AD (operating_system_id), INDEX IDX_D11814AB517FE9FE (equipment_id), INDEX IDX_D11814AB19EB6921 (client_id), UNIQUE INDEX UNIQ_D11814AB430C5E9 (intervention_report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_technician (intervention_id INT NOT NULL, technician_id INT NOT NULL, INDEX IDX_B0B993458EAE3863 (intervention_id), INDEX IDX_B0B99345E6C5D496 (technician_id), PRIMARY KEY(intervention_id, technician_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_task (intervention_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_5DC1C3E78EAE3863 (intervention_id), INDEX IDX_5DC1C3E78DB60186 (task_id), PRIMARY KEY(intervention_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_report (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_report_software (intervention_report_id INT NOT NULL, software_id INT NOT NULL, INDEX IDX_F4F1B8FF430C5E9 (intervention_report_id), INDEX IDX_F4F1B8FFD7452741 (software_id), PRIMARY KEY(intervention_report_id, software_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operating_system (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE software (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, color VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technician (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABA391D4AD FOREIGN KEY (operating_system_id) REFERENCES operating_system (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB430C5E9 FOREIGN KEY (intervention_report_id) REFERENCES intervention_report (id)');
        $this->addSql('ALTER TABLE intervention_technician ADD CONSTRAINT FK_B0B993458EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_technician ADD CONSTRAINT FK_B0B99345E6C5D496 FOREIGN KEY (technician_id) REFERENCES technician (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_task ADD CONSTRAINT FK_5DC1C3E78EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_task ADD CONSTRAINT FK_5DC1C3E78DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_report_software ADD CONSTRAINT FK_F4F1B8FF430C5E9 FOREIGN KEY (intervention_report_id) REFERENCES intervention_report (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_report_software ADD CONSTRAINT FK_F4F1B8FFD7452741 FOREIGN KEY (software_id) REFERENCES software (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB19EB6921');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB517FE9FE');
        $this->addSql('ALTER TABLE intervention_technician DROP FOREIGN KEY FK_B0B993458EAE3863');
        $this->addSql('ALTER TABLE intervention_task DROP FOREIGN KEY FK_5DC1C3E78EAE3863');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB430C5E9');
        $this->addSql('ALTER TABLE intervention_report_software DROP FOREIGN KEY FK_F4F1B8FF430C5E9');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814ABA391D4AD');
        $this->addSql('ALTER TABLE intervention_report_software DROP FOREIGN KEY FK_F4F1B8FFD7452741');
        $this->addSql('ALTER TABLE intervention_task DROP FOREIGN KEY FK_5DC1C3E78DB60186');
        $this->addSql('ALTER TABLE intervention_technician DROP FOREIGN KEY FK_B0B99345E6C5D496');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE intervention_technician');
        $this->addSql('DROP TABLE intervention_task');
        $this->addSql('DROP TABLE intervention_report');
        $this->addSql('DROP TABLE intervention_report_software');
        $this->addSql('DROP TABLE operating_system');
        $this->addSql('DROP TABLE software');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE technician');
        $this->addSql('DROP TABLE user');
    }
}
