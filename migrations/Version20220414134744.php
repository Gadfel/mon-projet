<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414134744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D693126F525E');
        $this->addSql('DROP INDEX IDX_D3D1D693126F525E ON invoice_line');
        $this->addSql('ALTER TABLE invoice_line CHANGE item_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6934584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D3D1D6934584665A ON invoice_line (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6934584665A');
        $this->addSql('DROP INDEX IDX_D3D1D6934584665A ON invoice_line');
        $this->addSql('ALTER TABLE invoice_line CHANGE product_id item_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D693126F525E FOREIGN KEY (item_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D3D1D693126F525E ON invoice_line (item_id)');
    }
}
