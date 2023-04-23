<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423001727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deposito ADD pais VARCHAR(20) NOT NULL, ADD provincia VARCHAR(20) NOT NULL, ADD ciudad VARCHAR(20) NOT NULL, ADD calle VARCHAR(50) NOT NULL, ADD altura INT NOT NULL, DROP direccion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deposito ADD direccion VARCHAR(255) NOT NULL, DROP pais, DROP provincia, DROP ciudad, DROP calle, DROP altura');
    }
}
