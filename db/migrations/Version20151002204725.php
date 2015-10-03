<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151002204725 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_tasks (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, created_date DATETIME NOT NULL, task_type INT NOT NULL, task_status INT NOT NULL, task_number VARCHAR(100) NOT NULL, createdBy_id INT DEFAULT NULL, INDEX IDX_601FB7CC3174800F (createdBy_id), INDEX IDX_601FB7CC166D1F9C (project_id), INDEX IDX_601FB7CC727ACA70 (parent_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_task_members (task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3E9D84848DB60186 (task_id), INDEX IDX_3E9D8484A76ED395 (user_id), PRIMARY KEY(task_id, user_id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_task_comment_attachment (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, extension VARCHAR(10) NOT NULL, uploaded_date DATETIME NOT NULL, is_deleted TINYINT(1) NOT NULL, uploadedBy_id INT DEFAULT NULL, INDEX IDX_BCF3DA60E91BE56 (uploadedBy_id), INDEX IDX_BCF3DA60F8697D13 (comment_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_task_attachments (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, extension VARCHAR(10) NOT NULL, uploaded_date DATETIME NOT NULL, is_deleted TINYINT(1) NOT NULL, uploadedBy_id INT DEFAULT NULL, INDEX IDX_3EF46F43E91BE56 (uploadedBy_id), INDEX IDX_3EF46F438DB60186 (task_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_task_comments (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, comment_number VARCHAR(255) NOT NULL, commented_date DATETIME NOT NULL, is_deleted TINYINT(1) NOT NULL, commentedBy_id INT DEFAULT NULL, INDEX IDX_983203C849A3B6FC (commentedBy_id), INDEX IDX_983203C88DB60186 (task_id), INDEX IDX_983203C8727ACA70 (parent_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_tasks ADD CONSTRAINT FK_601FB7CC3174800F FOREIGN KEY (createdBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_tasks ADD CONSTRAINT FK_601FB7CC166D1F9C FOREIGN KEY (project_id) REFERENCES ys_projects (id)");
        $this->addSql("ALTER TABLE ys_tasks ADD CONSTRAINT FK_601FB7CC727ACA70 FOREIGN KEY (parent_id) REFERENCES ys_tasks (id)");
        $this->addSql("ALTER TABLE ys_task_members ADD CONSTRAINT FK_3E9D84848DB60186 FOREIGN KEY (task_id) REFERENCES ys_tasks (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_task_members ADD CONSTRAINT FK_3E9D8484A76ED395 FOREIGN KEY (user_id) REFERENCES ys_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_task_comment_attachment ADD CONSTRAINT FK_BCF3DA60E91BE56 FOREIGN KEY (uploadedBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_task_comment_attachment ADD CONSTRAINT FK_BCF3DA60F8697D13 FOREIGN KEY (comment_id) REFERENCES ys_task_comments (id)");
        $this->addSql("ALTER TABLE ys_task_attachments ADD CONSTRAINT FK_3EF46F43E91BE56 FOREIGN KEY (uploadedBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_task_attachments ADD CONSTRAINT FK_3EF46F438DB60186 FOREIGN KEY (task_id) REFERENCES ys_tasks (id)");
        $this->addSql("ALTER TABLE ys_task_comments ADD CONSTRAINT FK_983203C849A3B6FC FOREIGN KEY (commentedBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_task_comments ADD CONSTRAINT FK_983203C88DB60186 FOREIGN KEY (task_id) REFERENCES ys_tasks (id)");
        $this->addSql("ALTER TABLE ys_task_comments ADD CONSTRAINT FK_983203C8727ACA70 FOREIGN KEY (parent_id) REFERENCES ys_task_comments (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_tasks DROP FOREIGN KEY FK_601FB7CC727ACA70");
        $this->addSql("ALTER TABLE ys_task_members DROP FOREIGN KEY FK_3E9D84848DB60186");
        $this->addSql("ALTER TABLE ys_task_attachments DROP FOREIGN KEY FK_3EF46F438DB60186");
        $this->addSql("ALTER TABLE ys_task_comments DROP FOREIGN KEY FK_983203C88DB60186");
        $this->addSql("ALTER TABLE ys_task_comment_attachment DROP FOREIGN KEY FK_BCF3DA60F8697D13");
        $this->addSql("ALTER TABLE ys_task_comments DROP FOREIGN KEY FK_983203C8727ACA70");
        $this->addSql("DROP TABLE ys_tasks");
        $this->addSql("DROP TABLE ys_task_members");
        $this->addSql("DROP TABLE ys_task_comment_attachment");
        $this->addSql("DROP TABLE ys_task_attachments");
        $this->addSql("DROP TABLE ys_task_comments");
    }
}
