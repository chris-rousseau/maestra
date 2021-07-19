<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210719095805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews_pills ADD pill_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reviews_pills ADD CONSTRAINT FK_30046186EACD9F12 FOREIGN KEY (pill_id) REFERENCES pills (id)');
        $this->addSql('ALTER TABLE reviews_pills ADD CONSTRAINT FK_30046186A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_30046186EACD9F12 ON reviews_pills (pill_id)');
        $this->addSql('CREATE INDEX IDX_30046186A76ED395 ON reviews_pills (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews_pills DROP FOREIGN KEY FK_30046186EACD9F12');
        $this->addSql('ALTER TABLE reviews_pills DROP FOREIGN KEY FK_30046186A76ED395');
        $this->addSql('DROP INDEX IDX_30046186EACD9F12 ON reviews_pills');
        $this->addSql('DROP INDEX IDX_30046186A76ED395 ON reviews_pills');
        $this->addSql('ALTER TABLE reviews_pills DROP pill_id, DROP user_id');
    }
}
