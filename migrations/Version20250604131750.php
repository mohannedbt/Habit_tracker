<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604131750 extends AbstractMigration
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
            ALTER TABLE daily_report DROP FOREIGN KEY FK_45114028E7AEB3B2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_45114028A76ED395 ON daily_report
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_45114028E7AEB3B2 ON daily_report
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report DROP user_id, DROP habit_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report ADD user_id INT DEFAULT NULL, ADD habit_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report ADD CONSTRAINT FK_45114028A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE daily_report ADD CONSTRAINT FK_45114028E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_45114028A76ED395 ON daily_report (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_45114028E7AEB3B2 ON daily_report (habit_id)
        SQL);
    }
}
