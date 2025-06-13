<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250613110629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ok_celiacs TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, delivery_time VARCHAR(255) NOT NULL, delivery_address VARCHAR(255) NOT NULL, payment_type VARCHAR(255) NOT NULL, payment_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pizza (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pizza_ingredient (pizza_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_6FF6C03FD41D1D42 (pizza_id), INDEX IDX_6FF6C03F933FE08C (ingredient_id), PRIMARY KEY(pizza_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pizza_order (id INT AUTO_INCREMENT NOT NULL, pizza_id INT NOT NULL, pizza_order_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_3589140D41D1D42 (pizza_id), INDEX IDX_35891401B312A6E (pizza_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_6FF6C03FD41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_ingredient ADD CONSTRAINT FK_6FF6C03F933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order ADD CONSTRAINT FK_3589140D41D1D42 FOREIGN KEY (pizza_id) REFERENCES pizza (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order ADD CONSTRAINT FK_35891401B312A6E FOREIGN KEY (pizza_order_id) REFERENCES `order` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_ingredient DROP FOREIGN KEY FK_6FF6C03FD41D1D42
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_ingredient DROP FOREIGN KEY FK_6FF6C03F933FE08C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order DROP FOREIGN KEY FK_3589140D41D1D42
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pizza_order DROP FOREIGN KEY FK_35891401B312A6E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ingredient
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `order`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pizza
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pizza_ingredient
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pizza_order
        SQL);
    }
}
