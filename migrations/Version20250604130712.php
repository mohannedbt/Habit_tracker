<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604130712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report DROP FOREIGN KEY FK_45114028A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report_habit DROP FOREIGN KEY FK_E33E6D2DCAD6BDDA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report_habit DROP FOREIGN KEY FK_E33E6D2DE7AEB3B2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE daily_report
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE daily_report_habit
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE daily_report (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, state VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, comment VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_45114028A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE daily_report_habit (daily_report_id INT NOT NULL, habit_id INT NOT NULL, INDEX IDX_E33E6D2DCAD6BDDA (daily_report_id), INDEX IDX_E33E6D2DE7AEB3B2 (habit_id), PRIMARY KEY(daily_report_id, habit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report ADD CONSTRAINT FK_45114028A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report_habit ADD CONSTRAINT FK_E33E6D2DCAD6BDDA FOREIGN KEY (daily_report_id) REFERENCES daily_report (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report_habit ADD CONSTRAINT FK_E33E6D2DE7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
    }
}
