<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150913153520 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_users (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, salt VARCHAR(255) NOT NULL, secrete VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, address LONGTEXT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, status INT NOT NULL, created DATETIME NOT NULL, api_key VARCHAR(6) NOT NULL, first_login TINYINT(1) NOT NULL, pwd_change_on DATETIME DEFAULT NULL, last_logged DATETIME DEFAULT NULL, resetToken VARCHAR(50) DEFAULT NULL, tokenRequested DATETIME DEFAULT NULL, tokenUsed DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_24C477B2E7927C74 (email), UNIQUE INDEX UNIQ_24C477B2C912ED9D (api_key), INDEX IDX_24C477B2FE54D947 (group_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, mtoOnly TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CE4F6DE5E237E06 (name), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_group_permission (group_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_8C1CBBFFE54D947 (group_id), INDEX IDX_8C1CBBFFED90CCA (permission_id), PRIMARY KEY(group_id, permission_id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_permissions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9A399D765E237E06 (name), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_users ADD CONSTRAINT FK_24C477B2FE54D947 FOREIGN KEY (group_id) REFERENCES ys_groups (id)");
        $this->addSql("ALTER TABLE ys_group_permission ADD CONSTRAINT FK_8C1CBBFFE54D947 FOREIGN KEY (group_id) REFERENCES ys_groups (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_group_permission ADD CONSTRAINT FK_8C1CBBFFED90CCA FOREIGN KEY (permission_id) REFERENCES ys_permissions (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_users DROP FOREIGN KEY FK_24C477B2FE54D947");
        $this->addSql("ALTER TABLE ys_group_permission DROP FOREIGN KEY FK_8C1CBBFFE54D947");
        $this->addSql("ALTER TABLE ys_group_permission DROP FOREIGN KEY FK_8C1CBBFFED90CCA");
        $this->addSql("DROP TABLE ys_users");
        $this->addSql("DROP TABLE ys_groups");
        $this->addSql("DROP TABLE ys_group_permission");
        $this->addSql("DROP TABLE ys_permissions");
    }
}
