<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602134120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_ligne ADD orders_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_ligne ADD CONSTRAINT FK_D46EAF7ECFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D46EAF7ECFFE9AD6 ON order_ligne (orders_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_ligne DROP FOREIGN KEY FK_D46EAF7ECFFE9AD6');
        $this->addSql('DROP INDEX IDX_D46EAF7ECFFE9AD6 ON order_ligne');
        $this->addSql('ALTER TABLE order_ligne DROP orders_id');
    }
}
