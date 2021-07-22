<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722091947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review_pill ADD user_id INT NOT NULL, ADD pill_id INT NOT NULL');
        $this->addSql('ALTER TABLE review_pill ADD CONSTRAINT FK_6285CBEFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review_pill ADD CONSTRAINT FK_6285CBEFEACD9F12 FOREIGN KEY (pill_id) REFERENCES pill (id)');
        $this->addSql('CREATE INDEX IDX_6285CBEFA76ED395 ON review_pill (user_id)');
        $this->addSql('CREATE INDEX IDX_6285CBEFEACD9F12 ON review_pill (pill_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review_pill DROP FOREIGN KEY FK_6285CBEFA76ED395');
        $this->addSql('ALTER TABLE review_pill DROP FOREIGN KEY FK_6285CBEFEACD9F12');
        $this->addSql('DROP INDEX IDX_6285CBEFA76ED395 ON review_pill');
        $this->addSql('DROP INDEX IDX_6285CBEFEACD9F12 ON review_pill');
        $this->addSql('ALTER TABLE review_pill DROP user_id, DROP pill_id');
    }
}
