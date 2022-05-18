<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517085632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calender (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, UNIQUE INDEX UNIQ_AC56442591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circuits (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, pays VARCHAR(100) NOT NULL, longeur INT NOT NULL, course_distance INT NOT NULL, description LONGTEXT DEFAULT NULL, capacite INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classement_equipes (id INT AUTO_INCREMENT NOT NULL, saisons_year INT DEFAULT NULL, equipes_equipe_id INT NOT NULL, points_total INT DEFAULT NULL, position INT DEFAULT NULL, INDEX fk_year2 (saisons_year), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classement_pilotes (id INT AUTO_INCREMENT NOT NULL, saisons_year INT DEFAULT NULL, pilotes_pilote_id INT NOT NULL, points_total INT DEFAULT NULL, position INT DEFAULT NULL, INDEX fk_year (saisons_year), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, circuitid_id INT DEFAULT NULL, saison_id INT DEFAULT NULL, organisateur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_course DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_A9A55A4C4B9988B1 (circuitid_id), INDEX IDX_A9A55A4CF965414C (saison_id), INDEX IDX_A9A55A4CD936B2FA (organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, voiture VARCHAR(255) NOT NULL, pays_origine VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membres (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, nationalite VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, INDEX IDX_594AE39C6D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, pilote_id INT DEFAULT NULL, equipe_id INT DEFAULT NULL, course_id INT DEFAULT NULL, qualifying_id INT DEFAULT NULL, grid INT DEFAULT NULL, position INT DEFAULT NULL, points INT DEFAULT NULL, INDEX IDX_AB55E24FF510AAE9 (pilote_id), INDEX IDX_AB55E24F6D861B89 (equipe_id), INDEX IDX_AB55E24F591CC992 (course_id), UNIQUE INDEX UNIQ_AB55E24F27C2F77C (qualifying_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pilotes (id INT NOT NULL, numero INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qualifying (id INT AUTO_INCREMENT NOT NULL, pilote_id INT NOT NULL, q1 VARCHAR(255) DEFAULT NULL, q2 VARCHAR(255) DEFAULT NULL, q3 VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, INDEX IDX_5072F7EAF510AAE9 (pilote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saisons (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_54469DF4591CC992 (course_id), INDEX IDX_54469DF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, tel VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, activation_token VARCHAR(255) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, img_status VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calender ADD CONSTRAINT FK_AC56442591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE classement_equipes ADD CONSTRAINT FK_1BF31BF822F602C8 FOREIGN KEY (saisons_year) REFERENCES saisons (id)');
        $this->addSql('ALTER TABLE classement_pilotes ADD CONSTRAINT FK_8E022A9422F602C8 FOREIGN KEY (saisons_year) REFERENCES saisons (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C4B9988B1 FOREIGN KEY (circuitid_id) REFERENCES circuits (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CF965414C FOREIGN KEY (saison_id) REFERENCES saisons (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CD936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE membres ADD CONSTRAINT FK_594AE39C6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FF510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilotes (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipes (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F27C2F77C FOREIGN KEY (qualifying_id) REFERENCES qualifying (id)');
        $this->addSql('ALTER TABLE qualifying ADD CONSTRAINT FK_5072F7EAF510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilotes (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C4B9988B1');
        $this->addSql('ALTER TABLE calender DROP FOREIGN KEY FK_AC56442591CC992');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F591CC992');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4591CC992');
        $this->addSql('ALTER TABLE membres DROP FOREIGN KEY FK_594AE39C6D861B89');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F6D861B89');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FF510AAE9');
        $this->addSql('ALTER TABLE qualifying DROP FOREIGN KEY FK_5072F7EAF510AAE9');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F27C2F77C');
        $this->addSql('ALTER TABLE classement_equipes DROP FOREIGN KEY FK_1BF31BF822F602C8');
        $this->addSql('ALTER TABLE classement_pilotes DROP FOREIGN KEY FK_8E022A9422F602C8');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CF965414C');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CD936B2FA');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4A76ED395');
        $this->addSql('DROP TABLE calender');
        $this->addSql('DROP TABLE circuits');
        $this->addSql('DROP TABLE classement_equipes');
        $this->addSql('DROP TABLE classement_pilotes');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE equipes');
        $this->addSql('DROP TABLE membres');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE pilotes');
        $this->addSql('DROP TABLE qualifying');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE saisons');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE user');
    }
}
