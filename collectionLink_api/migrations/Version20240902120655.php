<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902120655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sneaker DROP FOREIGN KEY FK_4259B88ABF396750');
        $this->addSql('ALTER TABLE sneaker_brand DROP FOREIGN KEY FK_CCED782944F5D008');
        $this->addSql('ALTER TABLE sneaker_brand DROP FOREIGN KEY FK_CCED7829B44896C4');
        $this->addSql('ALTER TABLE sneaker_color DROP FOREIGN KEY FK_B6E9C9987ADA1FB5');
        $this->addSql('ALTER TABLE sneaker_color DROP FOREIGN KEY FK_B6E9C998B44896C4');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE sneaker');
        $this->addSql('DROP TABLE sneaker_brand');
        $this->addSql('DROP TABLE sneaker_color');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hexa VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sneaker (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sneaker_brand (sneaker_id INT NOT NULL, brand_id INT NOT NULL, INDEX IDX_CCED782944F5D008 (brand_id), INDEX IDX_CCED7829B44896C4 (sneaker_id), PRIMARY KEY(sneaker_id, brand_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sneaker_color (sneaker_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_B6E9C9987ADA1FB5 (color_id), INDEX IDX_B6E9C998B44896C4 (sneaker_id), PRIMARY KEY(sneaker_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88ABF396750 FOREIGN KEY (id) REFERENCES collectable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_brand ADD CONSTRAINT FK_CCED782944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_brand ADD CONSTRAINT FK_CCED7829B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C9987ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C998B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE');
    }
}
