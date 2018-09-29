<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180929165539 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP INDEX UNIQ_232B318C649A58CD, ADD INDEX IDX_232B318C649A58CD (player_one_id)');
        $this->addSql('ALTER TABLE game DROP INDEX UNIQ_232B318CFC6BF02, ADD INDEX IDX_232B318CFC6BF02 (player_two_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP INDEX IDX_232B318C649A58CD, ADD UNIQUE INDEX UNIQ_232B318C649A58CD (player_one_id)');
        $this->addSql('ALTER TABLE game DROP INDEX IDX_232B318CFC6BF02, ADD UNIQUE INDEX UNIQ_232B318CFC6BF02 (player_two_id)');
    }
}
