<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150915153046 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, createdBy_id INT DEFAULT NULL, INDEX IDX_67CC016B3174800F (createdBy_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_project_members (project_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2380A43C166D1F9C (project_id), INDEX IDX_2380A43CA76ED395 (user_id), PRIMARY KEY(project_id, user_id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_project_meta (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, meta_key VARCHAR(255) NOT NULL, meta_value LONGTEXT DEFAULT NULL, INDEX IDX_8ABFA0BC166D1F9C (project_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_projects ADD CONSTRAINT FK_67CC016B3174800F FOREIGN KEY (createdBy_id) REFERENCES ys_users (id)");
        $this->addSql("ALTER TABLE ys_project_members ADD CONSTRAINT FK_2380A43C166D1F9C FOREIGN KEY (project_id) REFERENCES ys_projects (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_project_members ADD CONSTRAINT FK_2380A43CA76ED395 FOREIGN KEY (user_id) REFERENCES ys_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_project_meta ADD CONSTRAINT FK_8ABFA0BC166D1F9C FOREIGN KEY (project_id) REFERENCES ys_projects (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_project_members DROP FOREIGN KEY FK_2380A43C166D1F9C");
        $this->addSql("ALTER TABLE ys_project_meta DROP FOREIGN KEY FK_8ABFA0BC166D1F9C");
        $this->addSql("DROP TABLE ys_projects");
        $this->addSql("DROP TABLE ys_project_members");
        $this->addSql("DROP TABLE ys_project_meta");
    }
}
