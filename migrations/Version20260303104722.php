<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260303104722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe ADD CONSTRAINT FK_7A2E8EC8C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_employe ADD CONSTRAINT FK_7A2E8EC81B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe DROP FOREIGN KEY FK_7A2E8EC8C18272');
        $this->addSql('ALTER TABLE projet_employe DROP FOREIGN KEY FK_7A2E8EC81B65292');
    }
}
