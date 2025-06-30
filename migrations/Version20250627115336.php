<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627115336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD recipe_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13759D8A214 FOREIGN KEY (recipe_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA88B13759D8A214 ON recipe (recipe_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13759D8A214
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DA88B13759D8A214 ON recipe
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP recipe_id
        SQL);
    }
}
