<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016090119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partage ADD fichier_id INT NOT NULL');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6EF915CFE FOREIGN KEY (fichier_id) REFERENCES fichier (id)');
        $this->addSql('CREATE INDEX IDX_8B929E6EF915CFE ON partage (fichier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6EF915CFE');
        $this->addSql('DROP INDEX IDX_8B929E6EF915CFE ON partage');
        $this->addSql('ALTER TABLE partage DROP fichier_id');
    }
}
