<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260303092632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, deadline DATE NOT NULL, archive TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE projet_employe (projet_id INT NOT NULL, employe_id INT NOT NULL, INDEX IDX_7A2E8EC8C18272 (projet_id), INDEX IDX_7A2E8EC81B65292 (employe_id), PRIMARY KEY (projet_id, employe_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE projet_employe ADD CONSTRAINT FK_7A2E8EC8C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_employe ADD CONSTRAINT FK_7A2E8EC81B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_employe DROP FOREIGN KEY FK_7A2E8EC8C18272');
        $this->addSql('ALTER TABLE projet_employe DROP FOREIGN KEY FK_7A2E8EC81B65292');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_employe');
    }
}
