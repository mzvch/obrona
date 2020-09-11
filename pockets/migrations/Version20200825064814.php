<?php
declare (strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200825064814 extends AbstractMigration
{
    public function getDescription () : string
    {
        return 'Add financial transaction entity';
    }

    public function up (Schema $schema) : void
    {
        $this -> addSql ('CREATE TABLE financial_transaction (id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, pocket_id INT UNSIGNED NOT NULL, title VARCHAR (30) NOT NULL, amount NUMERIC (10, 2) NOT NULL, transaction_date DATETIME NOT NULL, post_transaction_balance NUMERIC (10, 2) NOT NULL, INDEX IDX_3000FF4DEB831F59 (pocket_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this -> addSql ('ALTER TABLE financial_transaction ADD CONSTRAINT FK_3000FF4DEB831F59 FOREIGN KEY (pocket_id) REFERENCES pocket (id)');
        $this -> addSql ('DROP INDEX id ON pocket');
        $this -> addSql ('ALTER TABLE pocket CHANGE id id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, CHANGE user_id user_id INT UNSIGNED NOT NULL, CHANGE account_balance account_balance NUMERIC (10, 2) DEFAULT \'0.00\'');
        $this -> addSql ('DROP INDEX id ON user');
        $this -> addSql ('ALTER TABLE user CHANGE id id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT');
    }

    public function down (Schema $schema) : void
    {
        $this -> addSql ('DROP TABLE financial_transaction');
        $this -> addSql ('ALTER TABLE pocket CHANGE id id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT, CHANGE user_id user_id INT UNSIGNED NOT NULL, CHANGE account_balance account_balance NUMERIC (10, 2) DEFAULT \'0.00\'');
        $this -> addSql ('CREATE UNIQUE INDEX id ON pocket (id)');
        $this -> addSql ('ALTER TABLE user CHANGE id id INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT');
        $this -> addSql ('CREATE UNIQUE INDEX id ON user (id)');
    }
}