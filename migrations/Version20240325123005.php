<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325123005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE soigner (id INT AUTO_INCREMENT NOT NULL, societe_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', employer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', patient_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', animal_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', acte_soins VARCHAR(50) DEFAULT NULL, traitement VARCHAR(255) DEFAULT NULL, nombre_fois VARCHAR(100) DEFAULT NULL, date_soins DATE DEFAULT NULL, INDEX IDX_6F551B19FCF77503 (societe_id), INDEX IDX_6F551B1941CD9E7A (employer_id), INDEX IDX_6F551B196B899279 (patient_id), INDEX IDX_6F551B198E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE soigner ADD CONSTRAINT FK_6F551B19FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE soigner ADD CONSTRAINT FK_6F551B1941CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('ALTER TABLE soigner ADD CONSTRAINT FK_6F551B196B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE soigner ADD CONSTRAINT FK_6F551B198E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE soigner DROP FOREIGN KEY FK_6F551B19FCF77503');
        $this->addSql('ALTER TABLE soigner DROP FOREIGN KEY FK_6F551B1941CD9E7A');
        $this->addSql('ALTER TABLE soigner DROP FOREIGN KEY FK_6F551B196B899279');
        $this->addSql('ALTER TABLE soigner DROP FOREIGN KEY FK_6F551B198E962C16');
        $this->addSql('DROP TABLE soigner');
    }
}
