<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241206232623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture ADD trajet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FD12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('CREATE INDEX IDX_E9E2810FD12A823 ON voiture (trajet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FD12A823');
        $this->addSql('DROP INDEX IDX_E9E2810FD12A823 ON voiture');
        $this->addSql('ALTER TABLE voiture DROP trajet_id');
    }
}
