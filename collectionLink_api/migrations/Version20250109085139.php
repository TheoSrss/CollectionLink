<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109085139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Ajout des colonnes en permettant temporairement NULL
        $this->addSql('ALTER TABLE collectable ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE collection_object ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        // Mise à jour des données existantes
        $this->addSql('UPDATE collectable SET created_at = NOW(), updated_at = NOW()');
        $this->addSql('UPDATE collection_object SET created_at = NOW(), updated_at = NOW()');
        $this->addSql('UPDATE user SET created_at = NOW(), updated_at = NOW()');

        // Modification des colonnes en NOT NULL
        $this->addSql('ALTER TABLE collectable MODIFY created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', MODIFY updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE collection_object MODIFY created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', MODIFY updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user MODIFY created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', MODIFY updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collectable DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE collection_object DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE `user` DROP created_at, DROP updated_at');
    }
}
