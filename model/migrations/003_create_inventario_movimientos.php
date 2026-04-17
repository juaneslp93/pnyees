<?php

class Migration_003_create_inventario_movimientos extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS `inventario_movimientos` (
                `id`            INT(11)                          NOT NULL AUTO_INCREMENT,
                `id_producto`   INT(11)                          NOT NULL,
                `tipo`          ENUM('entrada','salida','ajuste') NOT NULL,
                `cantidad`      INT(11)                          NOT NULL,
                `stock_antes`   INT(11)                          NOT NULL,
                `stock_despues` INT(11)                          NOT NULL,
                `referencia`    VARCHAR(100)                     DEFAULT NULL COMMENT 'nro_compra, nro_orden, ajuste',
                `motivo`        VARCHAR(200)                     DEFAULT NULL,
                `id_admin`      INT(11)                          DEFAULT NULL,
                `fecha`         DATETIME                         NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_producto` (`id_producto`),
                KEY `idx_fecha`    (`fecha`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(mysqli $db): void
    {
        $db->query("DROP TABLE IF EXISTS `inventario_movimientos`");
    }
}