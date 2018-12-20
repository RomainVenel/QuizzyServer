<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181220134736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_completion (id INT AUTO_INCREMENT NOT NULL, question_completion_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_575BBF405FD89DC0 (question_completion_id), INDEX IDX_575BBF40AA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE part (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_490F70C6853CD175 (quiz_id), INDEX IDX_490F70C6EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE part_completion (id INT AUTO_INCREMENT NOT NULL, quiz_completion_id INT NOT NULL, part_id INT NOT NULL, INDEX IDX_DA3B2BAED208596 (quiz_completion_id), INDEX IDX_DA3B2BA4CE34BEC (part_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, part_id INT NOT NULL, type_question_id INT NOT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, max_score INT NOT NULL, INDEX IDX_B6F7494E4CE34BEC (part_id), INDEX IDX_B6F7494E553E212E (type_question_id), INDEX IDX_B6F7494EEA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_completion (id INT AUTO_INCREMENT NOT NULL, part_completion_id INT NOT NULL, question_id INT NOT NULL, score INT NOT NULL, timer INT NOT NULL, INDEX IDX_5F02A8F637A48093 (part_completion_id), INDEX IDX_5F02A8F61E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, user INT NOT NULL, media_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_validated DATETIME DEFAULT NULL, popularity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_A412FA928D93D649 (user), INDEX IDX_A412FA92EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_completion (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, quiz_id INT NOT NULL, INDEX IDX_71B91042A76ED395 (user_id), INDEX IDX_71B91042853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_question (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, media INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_8D93D6496A2CA10C (media), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friend (user_id INT NOT NULL, friend_id INT NOT NULL, INDEX IDX_55EEAC61A76ED395 (user_id), INDEX IDX_55EEAC616A5458E8 (friend_id), PRIMARY KEY(user_id, friend_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_shared (user_id INT NOT NULL, quiz_id INT NOT NULL, INDEX IDX_FEC804E2A76ED395 (user_id), INDEX IDX_FEC804E2853CD175 (quiz_id), PRIMARY KEY(user_id, quiz_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer_completion ADD CONSTRAINT FK_575BBF405FD89DC0 FOREIGN KEY (question_completion_id) REFERENCES question_completion (id)');
        $this->addSql('ALTER TABLE answer_completion ADD CONSTRAINT FK_575BBF40AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id)');
        $this->addSql('ALTER TABLE part ADD CONSTRAINT FK_490F70C6853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE part ADD CONSTRAINT FK_490F70C6EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE part_completion ADD CONSTRAINT FK_DA3B2BAED208596 FOREIGN KEY (quiz_completion_id) REFERENCES quiz_completion (id)');
        $this->addSql('ALTER TABLE part_completion ADD CONSTRAINT FK_DA3B2BA4CE34BEC FOREIGN KEY (part_id) REFERENCES part (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E4CE34BEC FOREIGN KEY (part_id) REFERENCES part (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E553E212E FOREIGN KEY (type_question_id) REFERENCES type_question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE question_completion ADD CONSTRAINT FK_5F02A8F637A48093 FOREIGN KEY (part_completion_id) REFERENCES part_completion (id)');
        $this->addSql('ALTER TABLE question_completion ADD CONSTRAINT FK_5F02A8F61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA928D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE quiz_completion ADD CONSTRAINT FK_71B91042A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_completion ADD CONSTRAINT FK_71B91042853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496A2CA10C FOREIGN KEY (media) REFERENCES media (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC616A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_shared ADD CONSTRAINT FK_FEC804E2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_shared ADD CONSTRAINT FK_FEC804E2853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer_completion DROP FOREIGN KEY FK_575BBF40AA334807');
        $this->addSql('ALTER TABLE part DROP FOREIGN KEY FK_490F70C6EA9FDD75');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EEA9FDD75');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92EA9FDD75');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496A2CA10C');
        $this->addSql('ALTER TABLE part_completion DROP FOREIGN KEY FK_DA3B2BA4CE34BEC');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E4CE34BEC');
        $this->addSql('ALTER TABLE question_completion DROP FOREIGN KEY FK_5F02A8F637A48093');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question_completion DROP FOREIGN KEY FK_5F02A8F61E27F6BF');
        $this->addSql('ALTER TABLE answer_completion DROP FOREIGN KEY FK_575BBF405FD89DC0');
        $this->addSql('ALTER TABLE part DROP FOREIGN KEY FK_490F70C6853CD175');
        $this->addSql('ALTER TABLE quiz_completion DROP FOREIGN KEY FK_71B91042853CD175');
        $this->addSql('ALTER TABLE quiz_shared DROP FOREIGN KEY FK_FEC804E2853CD175');
        $this->addSql('ALTER TABLE part_completion DROP FOREIGN KEY FK_DA3B2BAED208596');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E553E212E');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA928D93D649');
        $this->addSql('ALTER TABLE quiz_completion DROP FOREIGN KEY FK_71B91042A76ED395');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61A76ED395');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC616A5458E8');
        $this->addSql('ALTER TABLE quiz_shared DROP FOREIGN KEY FK_FEC804E2A76ED395');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE answer_completion');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE part');
        $this->addSql('DROP TABLE part_completion');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_completion');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_completion');
        $this->addSql('DROP TABLE type_question');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE quiz_shared');
    }
}
