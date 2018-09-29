<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180929164342 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C649A58CD');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CFC6BF02');
        $this->addSql('DROP INDEX UNIQ_232B318C649A58CD ON game');
        $this->addSql('DROP INDEX UNIQ_232B318CFC6BF02 ON game');
        $this->addSql('ALTER TABLE game DROP player_one_id, DROP player_two_id');
        $this->addSql('ALTER TABLE user ADD game_as_white_id INT DEFAULT NULL, ADD game_as_black_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493C8093AF FOREIGN KEY (game_as_white_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64922D8363F FOREIGN KEY (game_as_black_id) REFERENCES game (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493C8093AF ON user (game_as_white_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64922D8363F ON user (game_as_black_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game ADD player_one_id INT DEFAULT NULL, ADD player_two_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C649A58CD FOREIGN KEY (player_one_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFC6BF02 FOREIGN KEY (player_two_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C649A58CD ON game (player_one_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CFC6BF02 ON game (player_two_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493C8093AF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64922D8363F');
        $this->addSql('DROP INDEX UNIQ_8D93D6493C8093AF ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64922D8363F ON user');
        $this->addSql('ALTER TABLE user DROP game_as_white_id, DROP game_as_black_id');
    }
}
