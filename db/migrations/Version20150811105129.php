<?php

namespace DBMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150811105129 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_tour_file_activities ADD market_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE ys_tour_file_activities ADD CONSTRAINT FK_67265706622F3F37 FOREIGN KEY (market_id) REFERENCES ys_markets (id)");
        $this->addSql("CREATE INDEX IDX_67265706622F3F37 ON ys_tour_file_activities (market_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE ys_tour_file_activities DROP FOREIGN KEY FK_67265706622F3F37");
        $this->addSql("DROP INDEX IDX_67265706622F3F37 ON ys_tour_file_activities");
        $this->addSql("ALTER TABLE ys_tour_file_activities DROP market_id");
    }
}
