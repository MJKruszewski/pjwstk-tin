<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201120144152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authorization_token (id INT AUTO_INCREMENT NOT NULL, crewmate_id INT NOT NULL, token VARCHAR(500) NOT NULL, expire_at DATETIME NOT NULL, INDEX IDX_6401A72B500FE4D4 (crewmate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crewmate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(600) NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crewmate_stations (id INT AUTO_INCREMENT NOT NULL, crewmate_id INT NOT NULL, station_id INT NOT NULL, date_from DATETIME NOT NULL, date_to DATETIME DEFAULT NULL, INDEX IDX_A36ED985500FE4D4 (crewmate_id), INDEX IDX_A36ED98521BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crewmate_stats (id INT AUTO_INCREMENT NOT NULL, crewmate_id INT NOT NULL, intelligence SMALLINT NOT NULL, strength SMALLINT NOT NULL, dexterity SMALLINT NOT NULL, experience SMALLINT NOT NULL, physical_condition SMALLINT NOT NULL, UNIQUE INDEX UNIQ_6E6F8A08500FE4D4 (crewmate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, code VARCHAR(15) NOT NULL, INDEX IDX_9F39F8B1AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station_task (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, station_id INT NOT NULL, INDEX IDX_BC4DFC188DB60186 (task_id), INDEX IDX_BC4DFC1821BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, reporter_id INT DEFAULT NULL, assignee_id INT DEFAULT NULL, code VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, priority SMALLINT DEFAULT NULL, INDEX IDX_527EDB25E1CFE6F5 (reporter_id), INDEX IDX_527EDB2559EC7D60 (assignee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE authorization_token ADD CONSTRAINT FK_6401A72B500FE4D4 FOREIGN KEY (crewmate_id) REFERENCES crewmate (id)');
        $this->addSql('ALTER TABLE crewmate_stations ADD CONSTRAINT FK_A36ED985500FE4D4 FOREIGN KEY (crewmate_id) REFERENCES crewmate (id)');
        $this->addSql('ALTER TABLE crewmate_stations ADD CONSTRAINT FK_A36ED98521BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE crewmate_stats ADD CONSTRAINT FK_6E6F8A08500FE4D4 FOREIGN KEY (crewmate_id) REFERENCES crewmate (id)');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE station_task ADD CONSTRAINT FK_BC4DFC188DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE station_task ADD CONSTRAINT FK_BC4DFC1821BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES crewmate (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2559EC7D60 FOREIGN KEY (assignee_id) REFERENCES crewmate (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authorization_token DROP FOREIGN KEY FK_6401A72B500FE4D4');
        $this->addSql('ALTER TABLE crewmate_stations DROP FOREIGN KEY FK_A36ED985500FE4D4');
        $this->addSql('ALTER TABLE crewmate_stats DROP FOREIGN KEY FK_6E6F8A08500FE4D4');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25E1CFE6F5');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2559EC7D60');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B1AE80F5DF');
        $this->addSql('ALTER TABLE crewmate_stations DROP FOREIGN KEY FK_A36ED98521BDB235');
        $this->addSql('ALTER TABLE station_task DROP FOREIGN KEY FK_BC4DFC1821BDB235');
        $this->addSql('ALTER TABLE station_task DROP FOREIGN KEY FK_BC4DFC188DB60186');
        $this->addSql('DROP TABLE authorization_token');
        $this->addSql('DROP TABLE crewmate');
        $this->addSql('DROP TABLE crewmate_stations');
        $this->addSql('DROP TABLE crewmate_stats');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE station_task');
        $this->addSql('DROP TABLE task');
    }
}
