<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625184456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE daily_report (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, habit_id INT DEFAULT NULL, date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, comment VARCHAR(255) NOT NULL, rating INT NOT NULL, INDEX IDX_45114028A76ED395 (user_id), INDEX IDX_45114028E7AEB3B2 (habit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report ADD CONSTRAINT FK_45114028A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report ADD CONSTRAINT FK_45114028E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE habit ADD user_id INT DEFAULT NULL, ADD color VARCHAR(7) NOT NULL, ADD start_date DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE habit ADD CONSTRAINT FK_44FE2172A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_44FE2172A76ED395 ON habit (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE habit DROP FOREIGN KEY FK_44FE2172A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report DROP FOREIGN KEY FK_45114028A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report DROP FOREIGN KEY FK_45114028E7AEB3B2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE daily_report
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_44FE2172A76ED395 ON habit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE habit DROP user_id, DROP color, DROP start_date
        SQL);
    }
}
