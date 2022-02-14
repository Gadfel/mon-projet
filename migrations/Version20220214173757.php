<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214173757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine ADD product_id INT NOT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF844584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF84A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1505DF844584665A ON machine (product_id)');
        $this->addSql('CREATE INDEX IDX_1505DF84A76ED395 ON machine (user_id)');
        $this->addSql('ALTER TABLE product ADD order_ligne_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBBBB6CC7 FOREIGN KEY (order_ligne_id) REFERENCES order_ligne (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADBBBB6CC7 ON product (order_ligne_id)');
        $this->addSql('ALTER TABLE user ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F5B7AF75 ON user (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE name name VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address1 address1 VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address2 address2 VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF844584665A');
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF84A76ED395');
        $this->addSql('DROP INDEX IDX_1505DF844584665A ON machine');
        $this->addSql('DROP INDEX IDX_1505DF84A76ED395 ON machine');
        $this->addSql('ALTER TABLE machine DROP product_id, DROP user_id, CHANGE name name VARCHAR(25) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE model model VARCHAR(45) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE img img VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(15) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE content_message content_message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `order` CHANGE date date VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBBBB6CC7');
        $this->addSql('DROP INDEX IDX_D34A04ADBBBB6CC7 ON product');
        $this->addSql('ALTER TABLE product DROP order_ligne_id, CHANGE name name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE img img VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('DROP INDEX IDX_8D93D649F5B7AF75 ON user');
        $this->addSql('ALTER TABLE user DROP address_id, CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(15) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE company_name company_name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
