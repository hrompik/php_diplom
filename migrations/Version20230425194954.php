<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425194954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D2294458B03A8386 ON feedback (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458B03A8386');
        $this->addSql('DROP INDEX IDX_D2294458B03A8386 ON feedback');
        $this->addSql('ALTER TABLE feedback DROP created_by_id');
    }
}
