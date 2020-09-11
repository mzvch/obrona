<?php
declare (strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200820150755 extends AbstractMigration
{
    public function getDescription () : string
    {
        return 'Add pocket entity';
    }

    public function up (Schema $schema) : void
    {
        $this -> addSql ('CREATE TABLE pocket (id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, user_id INT UNSIGNED NOT NULL, name VARCHAR (30) NOT NULL, account_balance NUMERIC (10, 2) DEFAULT 0, INDEX IDX_83711A15A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this -> addSql ('ALTER TABLE pocket ADD CONSTRAINT FK_83711A15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this -> addSql ('DROP INDEX id ON user');
        $this -> addSql ('ALTER TABLE user CHANGE id id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT');
    }

    public function down (Schema $schema) : void
    {
        $this -> addSql ('DROP TABLE pocket');
        $this -> addSql ('ALTER TABLE user CHANGE id id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT');
        $this -> addSql ('CREATE UNIQUE INDEX id ON user (id)');
    }
}