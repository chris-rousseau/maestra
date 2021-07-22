<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722085649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(128) NOT NULL, reimbursed SMALLINT NOT NULL, generic VARCHAR(255) DEFAULT NULL, posology VARCHAR(100) NOT NULL, type VARCHAR(64) DEFAULT NULL, generation SMALLINT NOT NULL, interruption TINYINT(1) NOT NULL, laboratory VARCHAR(64) NOT NULL, delay_intake SMALLINT NOT NULL, composition LONGTEXT DEFAULT NULL, count_reviews INT NOT NULL, slug VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pill');
    }
}
