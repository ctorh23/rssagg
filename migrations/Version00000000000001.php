<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version00000000000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial strcture of the \'rss_feeds\' table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `rss_feeds` (
            `id` INTEGER PRIMARY KEY,
            `name` TEXT NOT NULL,
            `url` TEXT NOT NULL,
            `frequency` INTEGER NOT NULL,
            `last_updated` INTEGER,
            `order` INTEGER DEFAULT 0,
            `active` INTEGER DEFAULT 1,
            `created_at` INTEGER NOT NULL,
            `updated_at` INTEGER,
            `deleted_at` INTEGER
            ) STRICT;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `rss_feeds`');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
