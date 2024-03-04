<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304151139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employer (id INT AUTO_INCREMENT NOT NULL, adresse_employer VARCHAR(255) NOT NULL, complement_adresse_employer VARCHAR(255) DEFAULT NULL, code_postal_employer VARCHAR(5) NOT NULL, ville_employer VARCHAR(50) NOT NULL, telephone_employer VARCHAR(10) NOT NULL, profession_employer VARCHAR(20) NOT NULL, images VARCHAR(255) DEFAULT NULL, date_creation_employer DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employer');
    }
}
