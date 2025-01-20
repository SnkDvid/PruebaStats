<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120020440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Primero agregamos la columna permitiendo NULL
        $this->addSql('ALTER TABLE clientes ADD fecha_creacion DATETIME NULL');
        
        // Actualizamos los registros existentes con la fecha actual
        $this->addSql('UPDATE clientes SET fecha_creacion = NOW() WHERE fecha_creacion IS NULL');
        
        // Finalmente, hacemos la columna NOT NULL
        $this->addSql('ALTER TABLE clientes MODIFY fecha_creacion DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clientes DROP fecha_creacion');
    }
}
