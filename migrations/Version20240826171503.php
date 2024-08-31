<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826171503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collectable (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_object (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5A88531AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_object_collectable (collection_object_id INT NOT NULL, collectable_id INT NOT NULL, INDEX IDX_93CB31DA505E649 (collection_object_id), INDEX IDX_93CB31DAA4EF7C48 (collectable_id), PRIMARY KEY(collection_object_id, collectable_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, hexa VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sneaker (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sneaker_brand (sneaker_id INT NOT NULL, brand_id INT NOT NULL, INDEX IDX_CCED7829B44896C4 (sneaker_id), INDEX IDX_CCED782944F5D008 (brand_id), PRIMARY KEY(sneaker_id, brand_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sneaker_color (sneaker_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_B6E9C998B44896C4 (sneaker_id), INDEX IDX_B6E9C9987ADA1FB5 (color_id), PRIMARY KEY(sneaker_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collection_object ADD CONSTRAINT FK_5A88531AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE collection_object_collectable ADD CONSTRAINT FK_93CB31DA505E649 FOREIGN KEY (collection_object_id) REFERENCES collection_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_object_collectable ADD CONSTRAINT FK_93CB31DAA4EF7C48 FOREIGN KEY (collectable_id) REFERENCES collectable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88ABF396750 FOREIGN KEY (id) REFERENCES collectable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_brand ADD CONSTRAINT FK_CCED7829B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_brand ADD CONSTRAINT FK_CCED782944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C998B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C9987ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collection_object DROP FOREIGN KEY FK_5A88531AA76ED395');
        $this->addSql('ALTER TABLE collection_object_collectable DROP FOREIGN KEY FK_93CB31DA505E649');
        $this->addSql('ALTER TABLE collection_object_collectable DROP FOREIGN KEY FK_93CB31DAA4EF7C48');
        $this->addSql('ALTER TABLE sneaker DROP FOREIGN KEY FK_4259B88ABF396750');
        $this->addSql('ALTER TABLE sneaker_brand DROP FOREIGN KEY FK_CCED7829B44896C4');
        $this->addSql('ALTER TABLE sneaker_brand DROP FOREIGN KEY FK_CCED782944F5D008');
        $this->addSql('ALTER TABLE sneaker_color DROP FOREIGN KEY FK_B6E9C998B44896C4');
        $this->addSql('ALTER TABLE sneaker_color DROP FOREIGN KEY FK_B6E9C9987ADA1FB5');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE collectable');
        $this->addSql('DROP TABLE collection_object');
        $this->addSql('DROP TABLE collection_object_collectable');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE sneaker');
        $this->addSql('DROP TABLE sneaker_brand');
        $this->addSql('DROP TABLE sneaker_color');
    }
}
