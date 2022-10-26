<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026115233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table medecin -(ManyToOne)-> ville -(ManyToOne)-> departement -(ManyToOne)-> region';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE departement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE medecin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE region_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ville_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE departement (id INT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1765B6398260155 ON departement (region_id)');
        $this->addSql('CREATE TABLE medecin (id INT NOT NULL, ville_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1BDA53C6A73F0036 ON medecin (ville_id)');
        $this->addSql('CREATE TABLE region (id INT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ville (id INT NOT NULL, departement_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_43C3D9C3CCF9E01E ON ville (departement_id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6398260155 FOREIGN KEY (region_id) REFERENCES region (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C6A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE departement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE medecin_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE region_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ville_id_seq CASCADE');
        $this->addSql('ALTER TABLE departement DROP CONSTRAINT FK_C1765B6398260155');
        $this->addSql('ALTER TABLE medecin DROP CONSTRAINT FK_1BDA53C6A73F0036');
        $this->addSql('ALTER TABLE ville DROP CONSTRAINT FK_43C3D9C3CCF9E01E');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE ville');
    }
}
