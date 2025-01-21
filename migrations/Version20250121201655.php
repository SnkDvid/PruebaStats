<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121201655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clientes ADD direccion VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE factura ADD razon_social VARCHAR(50) NOT NULL, ADD nombre_comercial VARCHAR(50) NOT NULL, ADD organizacion VARCHAR(50) NOT NULL, ADD tipo_tributario VARCHAR(50) NOT NULL, ADD tipo_municipio VARCHAR(50) NOT NULL, CHANGE nombre_producto nombre_producto VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clientes DROP direccion');
        $this->addSql('ALTER TABLE factura DROP razon_social, DROP nombre_comercial, DROP organizacion, DROP tipo_tributario, DROP tipo_municipio, CHANGE nombre_producto nombre_producto VARCHAR(255) NOT NULL');
    }
}
