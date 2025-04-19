<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419171843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE actuality (id SERIAL NOT NULL, user_id INT DEFAULT NULL, lieu VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, photo TEXT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4093DDD8A76ED395 ON actuality (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comments (id SERIAL NOT NULL, user_id INT DEFAULT NULL, actuality_id INT DEFAULT NULL, responses_id INT DEFAULT NULL, comment TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F9E962AA76ED395 ON comments (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F9E962AB84BD854 ON comments (actuality_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F9E962A91560F9D ON comments (responses_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actuality ADD CONSTRAINT FK_4093DDD8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AB84BD854 FOREIGN KEY (actuality_id) REFERENCES actuality (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A91560F9D FOREIGN KEY (responses_id) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER created_at SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voting ADD actuality_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55B84BD854 FOREIGN KEY (actuality_id) REFERENCES actuality (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FC28DA55B84BD854 ON voting (actuality_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voting DROP CONSTRAINT FK_FC28DA55B84BD854
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actuality DROP CONSTRAINT FK_4093DDD8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AB84BD854
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP CONSTRAINT FK_5F9E962A91560F9D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actuality
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comments
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649E7927C74
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER created_at DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FC28DA55B84BD854
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voting DROP actuality_id
        SQL);
    }
}
