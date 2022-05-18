<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220508115030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calender (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, UNIQUE INDEX UNIQ_AC56442591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, circuitid_id INT DEFAULT NULL, saison_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_course DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_A9A55A4C4B9988B1 (circuitid_id), INDEX IDX_A9A55A4CF965414C (saison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_54469DF4591CC992 (course_id), INDEX IDX_54469DF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calender ADD CONSTRAINT FK_AC56442591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C4B9988B1 FOREIGN KEY (circuitid_id) REFERENCES circuits (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE circuits ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE membres CHANGE equipe_id equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pilotes DROP FOREIGN KEY FK_614');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calender DROP FOREIGN KEY FK_AC56442591CC992');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4591CC992');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CF965414C');
        $this->addSql('DROP TABLE calender');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('ALTER TABLE circuits DROP image');
        $this->addSql('ALTER TABLE membres CHANGE equipe_id equipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE pilotes ADD CONSTRAINT FK_614 FOREIGN KEY (id) REFERENCES membres (id) ON DELETE CASCADE');
    }
}
