<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190518202014 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE friend_user (id INT AUTO_INCREMENT NOT NULL, user_sender INT NOT NULL, user INT NOT NULL, accepted TINYINT(1) NOT NULL, INDEX IDX_17E90AA93A5251F2 (user_sender), INDEX IDX_17E90AA98D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE friend_user ADD CONSTRAINT FK_17E90AA93A5251F2 FOREIGN KEY (user_sender) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend_user ADD CONSTRAINT FK_17E90AA98D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('DROP TABLE friend');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE friend (user_id INT NOT NULL, friend_id INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE friend_user');
    }
}
