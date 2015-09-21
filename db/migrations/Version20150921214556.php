<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150921214556 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_comments (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, createdBy_id INT DEFAULT NULL, INDEX IDX_64C124E53174800F (createdBy_id), INDEX IDX_64C124E5727ACA70 (parent_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_comments ADD CONSTRAINT FK_64C124E53174800F FOREIGN KEY (createdBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_comments ADD CONSTRAINT FK_64C124E5727ACA70 FOREIGN KEY (parent_id) REFERENCES ys_comments (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_comments DROP FOREIGN KEY FK_64C124E5727ACA70");
        $this->addSql("DROP TABLE ys_comments");
    }
}
