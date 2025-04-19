<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419194249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE user_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE voting_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comments (id SERIAL NOT NULL, author_id INT NOT NULL, report_id INT NOT NULL, parent_comment_id INT DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F9E962AF675F31B ON comments (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F9E962A4BD2A4C0 ON comments (report_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F9E962ABF2AF943 ON comments (parent_comment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, contact_info VARCHAR(255) NOT NULL, responsible_person VARCHAR(255) NOT NULL, company_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE friendships (id SERIAL NOT NULL, requesting_user_id INT NOT NULL, receiving_user_id INT NOT NULL, is_accepted BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_deleted BOOLEAN DEFAULT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_E0A8B7CA2A841BBC ON friendships (requesting_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E0A8B7CA49E25876 ON friendships (receiving_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN friendships.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN friendships.accepted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE neighborhoods (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notifications (id SERIAL NOT NULL, recipient_id INT NOT NULL, is_read BOOLEAN NOT NULL, title VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6000B0D3E92F8F78 ON notifications (recipient_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN notifications.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE offers (id SERIAL NOT NULL, creator_id INT NOT NULL, departure_location VARCHAR(255) NOT NULL, arrival_location VARCHAR(255) NOT NULL, price VARCHAR(255) DEFAULT NULL, contact_info VARCHAR(255) DEFAULT NULL, number_of_seats INT DEFAULT NULL, is_available BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA46042761220EA6 ON offers (creator_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN offers.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participants (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reports (id SERIAL NOT NULL, author_id INT DEFAULT NULL, location VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, photo_url TEXT DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F11FA745F675F31B ON reports (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reports.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reviews (id SERIAL NOT NULL, author_id INT NOT NULL, company_id INT NOT NULL, score INT DEFAULT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6970EB0FF675F31B ON reviews (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6970EB0F979B1AD6 ON reviews (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reviews.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE stations (id SERIAL NOT NULL, distributor VARCHAR(255) DEFAULT NULL, province VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, district VARCHAR(255) NOT NULL, commune VARCHAR(255) DEFAULT NULL, locality VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN stations.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE todos (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_completed BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE useful_numbers (id SERIAL NOT NULL, name VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "users" (id SERIAL NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, full_name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, username VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, avatar_url TEXT DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, points INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "users".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE votings (id SERIAL NOT NULL, user_id INT DEFAULT NULL, report_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F342AAA76ED395 ON votings (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F342AA4BD2A4C0 ON votings (report_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE za_mba_hoentos (id SERIAL NOT NULL, creator_id INT NOT NULL, departure_location VARCHAR(255) NOT NULL, arrival_location VARCHAR(255) NOT NULL, departure_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, contact_info VARCHAR(255) DEFAULT NULL, exact_location VARCHAR(255) DEFAULT NULL, seat_count INT DEFAULT NULL, preferences VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4E64FD2261220EA6 ON za_mba_hoentos (creator_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN za_mba_hoentos.departure_date IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN za_mba_hoentos.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4BD2A4C0 FOREIGN KEY (report_id) REFERENCES reports (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ABF2AF943 FOREIGN KEY (parent_comment_id) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friendships ADD CONSTRAINT FK_E0A8B7CA2A841BBC FOREIGN KEY (requesting_user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friendships ADD CONSTRAINT FK_E0A8B7CA49E25876 FOREIGN KEY (receiving_user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3E92F8F78 FOREIGN KEY (recipient_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offers ADD CONSTRAINT FK_DA46042761220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reports ADD CONSTRAINT FK_F11FA745F675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FF675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votings ADD CONSTRAINT FK_F342AAA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votings ADD CONSTRAINT FK_F342AA4BD2A4C0 FOREIGN KEY (report_id) REFERENCES reports (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE za_mba_hoentos ADD CONSTRAINT FK_4E64FD2261220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voting DROP CONSTRAINT fk_fc28da55ebb4b8ad
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE voting
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE voting_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, pseudo VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, avatar TEXT DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, point INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE voting (id SERIAL NOT NULL, voter_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_fc28da55ebb4b8ad ON voting (voter_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE voting ADD CONSTRAINT fk_fc28da55ebb4b8ad FOREIGN KEY (voter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP CONSTRAINT FK_5F9E962A4BD2A4C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP CONSTRAINT FK_5F9E962ABF2AF943
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friendships DROP CONSTRAINT FK_E0A8B7CA2A841BBC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE friendships DROP CONSTRAINT FK_E0A8B7CA49E25876
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notifications DROP CONSTRAINT FK_6000B0D3E92F8F78
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offers DROP CONSTRAINT FK_DA46042761220EA6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reports DROP CONSTRAINT FK_F11FA745F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0FF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reviews DROP CONSTRAINT FK_6970EB0F979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votings DROP CONSTRAINT FK_F342AAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votings DROP CONSTRAINT FK_F342AA4BD2A4C0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE za_mba_hoentos DROP CONSTRAINT FK_4E64FD2261220EA6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE friendships
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE neighborhoods
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notifications
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE offers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participants
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reports
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reviews
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE stations
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE todos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE useful_numbers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "users"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE votings
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE za_mba_hoentos
        SQL);
    }
}
