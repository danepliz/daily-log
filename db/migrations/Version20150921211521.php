<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150921211521 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_works (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, createdBy_id INT DEFAULT NULL, INDEX IDX_C6A2D0183174800F (createdBy_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_work_users (work_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5575FCB8BB3453DB (work_id), INDEX IDX_5575FCB8A76ED395 (user_id), PRIMARY KEY(work_id, user_id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_works ADD CONSTRAINT FK_C6A2D0183174800F FOREIGN KEY (createdBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_work_users ADD CONSTRAINT FK_5575FCB8BB3453DB FOREIGN KEY (work_id) REFERENCES ys_works (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_work_users ADD CONSTRAINT FK_5575FCB8A76ED395 FOREIGN KEY (user_id) REFERENCES ys_users (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_work_users DROP FOREIGN KEY FK_5575FCB8BB3453DB");
        $this->addSql("DROP TABLE ys_works");
        $this->addSql("DROP TABLE ys_work_users");
    }
}
