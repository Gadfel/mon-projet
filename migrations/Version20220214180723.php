<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214180723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD address_id INT NOT NULL, ADD order_ligne_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398BBBB6CC7 FOREIGN KEY (order_ligne_id) REFERENCES order_ligne (id)');
        $this->addSql('CREATE INDEX IDX_F5299398F5B7AF75 ON `order` (address_id)');
        $this->addSql('CREATE INDEX IDX_F5299398BBBB6CC7 ON `order` (order_ligne_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE name name VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address1 address1 VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address2 address2 VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE machine CHANGE name name VARCHAR(25) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE model model VARCHAR(45) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE img img VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(15) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE content_message content_message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398F5B7AF75');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398BBBB6CC7');
        $this->addSql('DROP INDEX IDX_F5299398F5B7AF75 ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398BBBB6CC7 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP address_id, DROP order_ligne_id, CHANGE date date VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product CHANGE name name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE img img VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(15) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE company_name company_name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
