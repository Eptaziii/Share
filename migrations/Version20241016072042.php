<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016072042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partage (id INT AUTO_INCREMENT NOT NULL, user_source_id INT NOT NULL, user_target_id INT DEFAULT NULL, nom_fichier VARCHAR(100) NOT NULL, INDEX IDX_8B929E6E95DC9185 (user_source_id), INDEX IDX_8B929E6E156E8682 (user_target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E95DC9185 FOREIGN KEY (user_source_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E156E8682 FOREIGN KEY (user_target_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, mdp VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6E95DC9185');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6E156E8682');
        $this->addSql('DROP TABLE partage');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin` COMMENT \'(DC2Type:json)\'');
    }
}
