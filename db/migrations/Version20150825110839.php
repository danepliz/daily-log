<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825110839 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE ys_transports (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, phones LONGTEXT NOT NULL COMMENT '(DC2Type:array)', status VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ys_transport_payable_currency_relations (transport_id INT NOT NULL, currency_id INT NOT NULL, INDEX IDX_CC3D5B359909C13F (transport_id), INDEX IDX_CC3D5B3538248176 (currency_id), PRIMARY KEY(transport_id, currency_id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ys_transport_payable_currency_relations ADD CONSTRAINT FK_CC3D5B359909C13F FOREIGN KEY (transport_id) REFERENCES ys_transports (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE ys_transport_payable_currency_relations ADD CONSTRAINT FK_CC3D5B3538248176 FOREIGN KEY (currency_id) REFERENCES ys_currencies (id) ON DELETE CASCADE");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_transport_payable_currency_relations DROP FOREIGN KEY FK_CC3D5B359909C13F");
        $this->addSql("DROP TABLE ys_transports");
        $this->addSql("DROP TABLE ys_transport_payable_currency_relations");
    }
}
