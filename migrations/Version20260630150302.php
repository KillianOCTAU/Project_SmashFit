<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260630150302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, nom_marque VARCHAR(100) NOT NULL, pays VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_5A6F91CEDAA00E99 (nom_marque), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, type VARCHAR(30) NOT NULL, description LONGTEXT NOT NULL, image_url VARCHAR(255) DEFAULT NULL, poids VARCHAR(10) DEFAULT NULL, equilibre VARCHAR(20) DEFAULT NULL, flexibilite VARCHAR(20) DEFAULT NULL, materiau VARCHAR(100) DEFAULT NULL, niveau_recommande VARCHAR(20) NOT NULL, prix NUMERIC(8, 2) DEFAULT NULL, marque_id INT NOT NULL, INDEX IDX_18D2B0914827B9B2 (marque_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, age INT DEFAULT NULL, club VARCHAR(150) DEFAULT NULL, numero_licence VARCHAR(20) DEFAULT NULL, niveau VARCHAR(30) DEFAULT NULL, style_jeu VARCHAR(30) DEFAULT NULL, frequence VARCHAR(20) DEFAULT NULL, utilisateur_id INT NOT NULL, UNIQUE INDEX UNIQ_E6D6B297FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recommandation (id INT AUTO_INCREMENT NOT NULL, score DOUBLE PRECISION NOT NULL, date_reco DATETIME NOT NULL, utilisateur_id INT NOT NULL, materiel_id INT NOT NULL, INDEX IDX_C7782A28FB88E14F (utilisateur_id), INDEX IDX_C7782A2816880AAF (materiel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, pseudo VARCHAR(60) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, date_inscription DATETIME NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), UNIQUE INDEX UNIQ_1D1C63B386CC499D (pseudo), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0914827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE recommandation ADD CONSTRAINT FK_C7782A28FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE recommandation ADD CONSTRAINT FK_C7782A2816880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0914827B9B2');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297FB88E14F');
        $this->addSql('ALTER TABLE recommandation DROP FOREIGN KEY FK_C7782A28FB88E14F');
        $this->addSql('ALTER TABLE recommandation DROP FOREIGN KEY FK_C7782A2816880AAF');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE recommandation');
        $this->addSql('DROP TABLE utilisateur');
    }
}
