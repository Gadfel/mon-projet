<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602133243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398BBBB6CC7');
        $this->addSql('DROP INDEX IDX_F5299398BBBB6CC7 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_ligne_id, CHANGE date date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD order_ligne_id INT NOT NULL, CHANGE date date VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398BBBB6CC7 FOREIGN KEY (order_ligne_id) REFERENCES order_ligne (id)');
        $this->addSql('CREATE INDEX IDX_F5299398BBBB6CC7 ON `order` (order_ligne_id)');
    }
}
