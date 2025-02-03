<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131135609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collectable_collection_object (collectable_id INT NOT NULL, collection_object_id INT NOT NULL, INDEX IDX_8C66831AA4EF7C48 (collectable_id), INDEX IDX_8C66831A505E649 (collection_object_id), PRIMARY KEY(collectable_id, collection_object_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collectable_collection_object ADD CONSTRAINT FK_8C66831AA4EF7C48 FOREIGN KEY (collectable_id) REFERENCES collectable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collectable_collection_object ADD CONSTRAINT FK_8C66831A505E649 FOREIGN KEY (collection_object_id) REFERENCES collection_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_object_collectable DROP FOREIGN KEY FK_93CB31DAA4EF7C48');
        $this->addSql('ALTER TABLE collection_object_collectable DROP FOREIGN KEY FK_93CB31DA505E649');
        $this->addSql('DROP TABLE collection_object_collectable');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collection_object_collectable (collection_object_id INT NOT NULL, collectable_id INT NOT NULL, INDEX IDX_93CB31DAA4EF7C48 (collectable_id), INDEX IDX_93CB31DA505E649 (collection_object_id), PRIMARY KEY(collection_object_id, collectable_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE collection_object_collectable ADD CONSTRAINT FK_93CB31DAA4EF7C48 FOREIGN KEY (collectable_id) REFERENCES collectable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_object_collectable ADD CONSTRAINT FK_93CB31DA505E649 FOREIGN KEY (collection_object_id) REFERENCES collection_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collectable_collection_object DROP FOREIGN KEY FK_8C66831AA4EF7C48');
        $this->addSql('ALTER TABLE collectable_collection_object DROP FOREIGN KEY FK_8C66831A505E649');
        $this->addSql('DROP TABLE collectable_collection_object');
    }
}
