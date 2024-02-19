<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129150848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE categorias (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidoproducto (id INT AUTO_INCREMENT NOT NULL, pedido_id INT DEFAULT NULL, producto_id INT DEFAULT NULL, unidades INT DEFAULT NULL, PRIMARY KEY(id), INDEX IDX_pedido_id (pedido_id), INDEX IDX_producto_id (producto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos (id INT AUTO_INCREMENT NOT NULL, fecha DATE DEFAULT NULL, usuario_id INT DEFAULT NULL, PRIMARY KEY(id), INDEX IDX_usuario_id (usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) DEFAULT NULL, precio DECIMAL(10,2) DEFAULT NULL, stock INT DEFAULT NULL, descripcion TEXT DEFAULT NULL, categoria_id INT DEFAULT NULL, PRIMARY KEY(id), INDEX IDX_categoria_id (categoria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reabastece (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, producto_id INT DEFAULT NULL, unidades INT DEFAULT NULL, PRIMARY KEY(id), INDEX IDX_usuario_id (usuario_id), INDEX IDX_producto_id (producto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, correo VARCHAR(100) DEFAULT NULL, contraseña VARCHAR(100) DEFAULT NULL, permisos INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE pedidoproducto ADD CONSTRAINT FK_pedido_id FOREIGN KEY (pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE pedidoproducto ADD CONSTRAINT FK_producto_id FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_usuario_id FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE productos ADD CONSTRAINT FK_categoria_id FOREIGN KEY (categoria_id) REFERENCES categorias (id)');
        $this->addSql('ALTER TABLE reabastece ADD CONSTRAINT FK_usuario_id FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reabastece ADD CONSTRAINT FK_producto_id FOREIGN KEY (producto_id) REFERENCES productos (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE categorias');
        $this->addSql('DROP TABLE pedidoproducto');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('DROP TABLE productos');
        $this->addSql('DROP TABLE reabastece');
        $this->addSql('DROP TABLE user');
    }
}