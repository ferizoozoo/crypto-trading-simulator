<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324194332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD COLUMN timestamp INTEGER NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__trades AS SELECT id, buyer_id, seller_id, price, amount, timestamp FROM trades');
        $this->addSql('DROP TABLE trades');
        $this->addSql('CREATE TABLE trades (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, buyer_id INTEGER NOT NULL, seller_id INTEGER NOT NULL, price DOUBLE PRECISION NOT NULL, amount INTEGER NOT NULL, time DATETIME NOT NULL, CONSTRAINT FK_BFA111256C755722 FOREIGN KEY (buyer_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BFA111258DE820D9 FOREIGN KEY (seller_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO trades (id, buyer_id, seller_id, price, amount, time) SELECT id, buyer_id, seller_id, price, amount, timestamp FROM __temp__trades');
        $this->addSql('DROP TABLE __temp__trades');
        $this->addSql('CREATE INDEX IDX_BFA111258DE820D9 ON trades (seller_id)');
        $this->addSql('CREATE INDEX IDX_BFA111256C755722 ON trades (buyer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, user_id, amount, price, type FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, amount INTEGER NOT NULL, price NUMERIC(10, 2) NOT NULL, type VARCHAR(8) NOT NULL, CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO orders (id, user_id, amount, price, type) SELECT id, user_id, amount, price, type FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__trades AS SELECT id, buyer_id, seller_id, price, amount, time FROM trades');
        $this->addSql('DROP TABLE trades');
        $this->addSql('CREATE TABLE trades (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, buyer_id INTEGER NOT NULL, seller_id INTEGER NOT NULL, price DOUBLE PRECISION NOT NULL, amount INTEGER NOT NULL, timestamp DATETIME NOT NULL, CONSTRAINT FK_BFA111256C755722 FOREIGN KEY (buyer_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BFA111258DE820D9 FOREIGN KEY (seller_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO trades (id, buyer_id, seller_id, price, amount, timestamp) SELECT id, buyer_id, seller_id, price, amount, time FROM __temp__trades');
        $this->addSql('DROP TABLE __temp__trades');
        $this->addSql('CREATE INDEX IDX_BFA111256C755722 ON trades (buyer_id)');
        $this->addSql('CREATE INDEX IDX_BFA111258DE820D9 ON trades (seller_id)');
    }
}
