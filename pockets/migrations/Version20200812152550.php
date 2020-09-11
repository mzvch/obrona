<?php
declare (strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200812152550 extends AbstractMigration
{
    public function getDescription () : string
    {
        return "User entity migration";
    }

    public function up (Schema $schema) : void
    {
        $this -> addSql ('CREATE TABLE user (id INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT, email VARCHAR (50) NOT NULL, password VARCHAR (255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
    }

    public function down (Schema $schema) : void
    {
        $this -> addSql ('DROP TABLE user');
    }
}