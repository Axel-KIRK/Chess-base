<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180924143445 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sessions (sess_id VARCHAR(128) NOT NULL COLLATE utf8_bin, sess_data BLOB NOT NULL, sess_time INT UNSIGNED NOT NULL, sess_lifetime INT NOT NULL, PRIMARY KEY(sess_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
