<?php
declare (strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200814151641 extends AbstractMigration
{
    public function getDescription () : string
    {
        return 'Dodanie pola imię i nazwisko dla encji użytkownika';
    }

    public function up (Schema $schema) : void
    {
        $this -> addSql ('DROP INDEX id ON user');
        $this -> addSql ('ALTER TABLE user ADD first_name VARCHAR(30) NOT NULL AFTER id, ADD last_name VARCHAR(50) NOT NULL AFTER first_name, CHANGE id id INT UNSIGNED UNIQUE AUTO_INCREMENT NOT NULL');
    }

    public function down (Schema $schema) : void
    {
        $this -> addSql ('ALTER TABLE user DROP first_name, DROP last_name, CHANGE id id INT UNSIGNED UNIQUE AUTO_INCREMENT NOT NULL');
        $this -> addSql ('CREATE UNIQUE INDEX id ON user (id)');
    }
}