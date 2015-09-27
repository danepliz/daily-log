<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150927214327 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_project_attachments (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type INT NOT NULL, uploadedTime DATETIME NOT NULL, deleted TINYINT(1) NOT NULL, extension VARCHAR(10) NOT NULL, uploadedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX IDX_42154F13E91BE56 (uploadedBy_id), INDEX IDX_42154F1363D8C20E (deletedBy_id), INDEX IDX_42154F13166D1F9C (project_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_project_attachments ADD CONSTRAINT FK_42154F13E91BE56 FOREIGN KEY (uploadedBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_project_attachments ADD CONSTRAINT FK_42154F1363D8C20E FOREIGN KEY (deletedBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_project_attachments ADD CONSTRAINT FK_42154F13166D1F9C FOREIGN KEY (project_id) REFERENCES ys_projects (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("DROP TABLE ys_project_attachments");
    }
}
