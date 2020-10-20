<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018152328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crewmate (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_A5A8BCE9AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9F39F8B1AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station_crewmate (station_id INT NOT NULL, crewmate_id INT NOT NULL, INDEX IDX_293266BF21BDB235 (station_id), INDEX IDX_293266BF500FE4D4 (crewmate_id), PRIMARY KEY(station_id, crewmate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, reporter_id INT NOT NULL, status_id INT NOT NULL, assignee_id INT DEFAULT NULL, priority INT NOT NULL, INDEX IDX_527EDB25E1CFE6F5 (reporter_id), INDEX IDX_527EDB256BF700BD (status_id), INDEX IDX_527EDB2559EC7D60 (assignee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_status (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, crewmate_id INT NOT NULL, expire_at DATETIME NOT NULL, hash VARCHAR(255) NOT NULL, INDEX IDX_5F37A13B500FE4D4 (crewmate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE crewmate ADD CONSTRAINT FK_A5A8BCE9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE station_crewmate ADD CONSTRAINT FK_293266BF21BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE station_crewmate ADD CONSTRAINT FK_293266BF500FE4D4 FOREIGN KEY (crewmate_id) REFERENCES crewmate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES crewmate (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256BF700BD FOREIGN KEY (status_id) REFERENCES task_status (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2559EC7D60 FOREIGN KEY (assignee_id) REFERENCES crewmate (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B500FE4D4 FOREIGN KEY (crewmate_id) REFERENCES crewmate (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE station_crewmate DROP FOREIGN KEY FK_293266BF500FE4D4');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25E1CFE6F5');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2559EC7D60');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B500FE4D4');
        $this->addSql('ALTER TABLE crewmate DROP FOREIGN KEY FK_A5A8BCE9AE80F5DF');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B1AE80F5DF');
        $this->addSql('ALTER TABLE station_crewmate DROP FOREIGN KEY FK_293266BF21BDB235');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256BF700BD');
        $this->addSql('DROP TABLE crewmate');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE station_crewmate');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_status');
        $this->addSql('DROP TABLE token');
    }
}
