<?php

class Migration_005_create_ordenes_proveedor extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS `ordenes_proveedor` (
                `id`              INT(11)                                    NOT NULL AUTO_INCREMENT,
                `numero_orden`    VARCHAR(50)                                NOT NULL,
                `proveedor`       VARCHAR(150)                               NOT NULL,
                `notas`           TEXT                                       DEFAULT NULL,
                `fecha`           DATETIME                                   NOT NULL,
                `estado`          ENUM('pendiente','recibida','cancelada')   NOT NULL DEFAULT 'pendiente',
                `fecha_recepcion` DATETIME                                   DEFAULT NULL,
                `id_admin`        INT(11)                                    NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                KEY `idx_estado` (`estado`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `ordenes_proveedor_detalles` (
                `id`              INT(11)       NOT NULL AUTO_INCREMENT,
                `id_orden`        INT(11)       NOT NULL,
                `id_producto`     INT(11)       NOT NULL,
                `nombre_producto` VARCHAR(150)  NOT NULL,
                `cantidad`        INT(11)       NOT NULL,
                `precio_compra`   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                PRIMARY KEY (`id`),
                KEY `idx_orden`   (`id_orden`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(mysqli $db): void
    {
        $db->query("DROP TABLE IF EXISTS `ordenes_proveedor_detalles`");
        $db->query("DROP TABLE IF EXISTS `ordenes_proveedor`");
    }
}
