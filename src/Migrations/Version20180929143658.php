<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180929143658 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_answer (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, article_id INT NOT NULL, body LONGTEXT NOT NULL, publish_at DATETIME NOT NULL, INDEX IDX_B01ADDA5F675F31B (author_id), INDEX IDX_B01ADDA57294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, publish_at DATETIME NOT NULL, INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, title VARCHAR(250) NOT NULL, body LONGTEXT NOT NULL, sent_at DATETIME NOT NULL, deleted_by_sender TINYINT(1) NOT NULL, deleted_by_receiver TINYINT(1) NOT NULL, read_by_receiver TINYINT(1) NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, player_who_can_play_id INT DEFAULT NULL, player_one_id INT DEFAULT NULL, player_two_id INT DEFAULT NULL, chess_board LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', movement_list LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', started_at DATETIME NOT NULL, last_move VARCHAR(50) DEFAULT NULL, last_move_time DATETIME DEFAULT NULL, INDEX IDX_232B318C1CF398FB (player_who_can_play_id), UNIQUE INDEX UNIQ_232B318C649A58CD (player_one_id), UNIQUE INDEX UNIQ_232B318CFC6BF02 (player_two_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats (id INT AUTO_INCREMENT NOT NULL, nb_win INT DEFAULT NULL, nb_game INT NOT NULL, nb_lose INT DEFAULT NULL, nb_surrender INT DEFAULT NULL, total_time TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, stats_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, google_access_token VARCHAR(255) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, first_login DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_8D93D64970AA3482 (stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friends (user_id INT NOT NULL, friend_user_id INT NOT NULL, INDEX IDX_21EE7069A76ED395 (user_id), INDEX IDX_21EE706993D1119E (friend_user_id), PRIMARY KEY(user_id, friend_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_over (id INT AUTO_INCREMENT NOT NULL, opponent_id INT DEFAULT NULL, player_id INT NOT NULL, movement_list LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', duration TIME DEFAULT NULL, is_winner TINYINT(1) NOT NULL, INDEX IDX_5FE9CF757F656CDC (opponent_id), INDEX IDX_5FE9CF7599E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_answer ADD CONSTRAINT FK_B01ADDA5F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_answer ADD CONSTRAINT FK_B01ADDA57294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1CF398FB FOREIGN KEY (player_who_can_play_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C649A58CD FOREIGN KEY (player_one_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFC6BF02 FOREIGN KEY (player_two_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64970AA3482 FOREIGN KEY (stats_id) REFERENCES stats (id)');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE7069A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friends ADD CONSTRAINT FK_21EE706993D1119E FOREIGN KEY (friend_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_over ADD CONSTRAINT FK_5FE9CF757F656CDC FOREIGN KEY (opponent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_over ADD CONSTRAINT FK_5FE9CF7599E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_answer DROP FOREIGN KEY FK_B01ADDA57294869C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64970AA3482');
        $this->addSql('ALTER TABLE article_answer DROP FOREIGN KEY FK_B01ADDA5F675F31B');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C1CF398FB');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C649A58CD');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CFC6BF02');
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE7069A76ED395');
        $this->addSql('ALTER TABLE friends DROP FOREIGN KEY FK_21EE706993D1119E');
        $this->addSql('ALTER TABLE game_over DROP FOREIGN KEY FK_5FE9CF757F656CDC');
        $this->addSql('ALTER TABLE game_over DROP FOREIGN KEY FK_5FE9CF7599E6F5DF');
        $this->addSql('DROP TABLE article_answer');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE stats');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE friends');
        $this->addSql('DROP TABLE game_over');
    }
}
