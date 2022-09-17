<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220916151617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds the necessary queries to add the required schema to the database';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                CREATE TABLE addresses (
                    id INT AUTO_INCREMENT NOT NULL,
                    user_id INT NOT NULL,
                    address VARCHAR(255) NOT NULL,
                    city VARCHAR(20) NOT NULL,
                    postal_code VARCHAR(6) NOT NULL,
                    state VARCHAR(20) NOT NULL,
                    INDEX IDX_D4E6F81A76ED395 (user_id),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );

        $this->addSql(
            <<<SQL
                CREATE TABLE banks (
                    id INT AUTO_INCREMENT NOT NULL,
                    card_expire VARCHAR(5) NOT NULL,
                    card_number VARCHAR(16) NOT NULL,
                    card_type VARCHAR(10) NOT NULL,
                    currency VARCHAR(10) NOT NULL,
                    iban VARCHAR(50) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );

        $this->addSql(
            <<<SQL
                CREATE TABLE hairs (
                    id INT AUTO_INCREMENT NOT NULL,
                    user_id INT DEFAULT NULL,
                    color VARCHAR(20) NOT NULL,
                    type VARCHAR(20) NOT NULL,
                    UNIQUE INDEX UNIQ_9CF76119A76ED395 (user_id),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );

        $this->addSql(
            <<<SQL
                CREATE TABLE posts (
                    id INT AUTO_INCREMENT NOT NULL,
                    user_id INT NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    body LONGTEXT NOT NULL,
                    reactions INT NULL DEFAULT 0,
                    INDEX IDX_5A8A6C8DA76ED395 (user_id),
                    PRIMARY KEY(id),
                    UNIQUE(title)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );

        $this->addSql(
            <<<SQL
                CREATE TABLE users (
                    id INT AUTO_INCREMENT NOT NULL,
                    first_name VARCHAR(255) NOT NULL,
                    last_name VARCHAR(255) NOT NULL,
                    username VARCHAR(50) NOT NULL,
                    email VARCHAR(50) NOT NULL,
                    phone VARCHAR(20) NULL,
                    birth_date DATETIME NULL,
                    height DECIMAL(5, 2) NULL,
                    weight DECIMAL(5, 2) NULL,
                    PRIMARY KEY(id),
                    UNIQUE(first_name, last_name),
                    UNIQUE(email)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );

        $this->addSql(
            <<<SQL
                ALTER TABLE addresses ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
            SQL
        );

        $this->addSql(
            <<<SQL
                ALTER TABLE hairs ADD CONSTRAINT FK_9CF76119A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
            SQL
        );

        $this->addSql(
            <<<SQL
                ALTER TABLE posts ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
            SQL
        );

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE hairs DROP FOREIGN KEY FK_9CF76119A76ED395');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE banks');
        $this->addSql('DROP TABLE hairs');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE users');
    }
}
