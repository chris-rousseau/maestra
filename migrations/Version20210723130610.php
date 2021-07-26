<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210723130610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill ADD score_acne INT DEFAULT NULL, ADD score_libido INT DEFAULT NULL, ADD score_migraine INT DEFAULT NULL, ADD score_weight INT DEFAULT NULL, ADD score_breast_pain INT DEFAULT NULL, ADD score_nausea INT DEFAULT NULL, ADD score_pms INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pill DROP score_acne, DROP score_libido, DROP score_migraine, DROP score_weight, DROP score_breast_pain, DROP score_nausea, DROP score_pms');
    }
}
