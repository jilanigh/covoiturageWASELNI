<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241206204717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98C85C0B3BE');
        $this->addSql('DROP INDEX IDX_2B5BA98C85C0B3BE ON trajet');
        $this->addSql('ALTER TABLE trajet ADD place_dispo INT NOT NULL, DROP chauffeur_id, DROP places_disponibles, CHANGE prix prix INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD is_verified TINYINT(1) NOT NULL, DROP first_name, DROP last_name, DROP phone, DROP disc, DROP rating, DROP experience, DROP available, DROP preferences');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F85C0B3BE');
        $this->addSql('DROP INDEX UNIQ_IMMATRICULATION ON voiture');
        $this->addSql('DROP INDEX IDX_E9E2810F85C0B3BE ON voiture');
        $this->addSql('ALTER TABLE voiture DROP chauffeur_id, CHANGE annee_fabrication annee_fabrication DATE NOT NULL, CHANGE couleur couleur VARCHAR(255) NOT NULL, CHANGE immatriculation immat VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE trajet ADD places_disponibles INT NOT NULL, CHANGE prix prix DOUBLE PRECISION NOT NULL, CHANGE place_dispo chauffeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98C85C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2B5BA98C85C0B3BE ON trajet (chauffeur_id)');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(50) DEFAULT NULL, ADD last_name VARCHAR(50) NOT NULL, ADD phone INT NOT NULL, ADD disc VARCHAR(255) NOT NULL, ADD rating DOUBLE PRECISION DEFAULT NULL, ADD experience SMALLINT DEFAULT NULL, ADD available TINYINT(1) DEFAULT NULL, ADD preferences VARCHAR(255) DEFAULT NULL, DROP roles, DROP is_verified');
        $this->addSql('ALTER TABLE voiture ADD chauffeur_id INT NOT NULL, CHANGE annee_fabrication annee_fabrication DATE DEFAULT NULL, CHANGE couleur couleur VARCHAR(10) DEFAULT NULL, CHANGE immat immatriculation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F85C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IMMATRICULATION ON voiture (immatriculation)');
        $this->addSql('CREATE INDEX IDX_E9E2810F85C0B3BE ON voiture (chauffeur_id)');
    }
}
