<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210723130845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill CHANGE score_acne score_acne INT DEFAULT 0, CHANGE score_libido score_libido INT DEFAULT 0, CHANGE score_migraine score_migraine INT DEFAULT 0, CHANGE score_weight score_weight INT DEFAULT 0, CHANGE score_breast_pain score_breast_pain INT DEFAULT 0, CHANGE score_nausea score_nausea INT DEFAULT 0, CHANGE score_pms score_pms INT DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill CHANGE score_acne score_acne INT DEFAULT NULL, CHANGE score_libido score_libido INT DEFAULT NULL, CHANGE score_migraine score_migraine INT DEFAULT NULL, CHANGE score_weight score_weight INT DEFAULT NULL, CHANGE score_breast_pain score_breast_pain INT DEFAULT NULL, CHANGE score_nausea score_nausea INT DEFAULT NULL, CHANGE score_pms score_pms INT DEFAULT NULL');
    }
}
