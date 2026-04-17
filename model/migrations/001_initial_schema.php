<?php

class Migration_001_initial_schema extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS `roles` (
                `id`     INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(45)   NOT NULL,
                `estado` ENUM('0','1') NOT NULL DEFAULT '0',
                `fecha`  DATETIME      NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `admin` (
                `id`        INT(11)       NOT NULL AUTO_INCREMENT,
                `usuario`   VARCHAR(45)   NOT NULL,
                `clave`     VARCHAR(100)  CHARACTER SET utf8 NOT NULL,
                `nombre`    VARCHAR(45)   NOT NULL,
                `telefono`  INT(11)       DEFAULT NULL,
                `correo`    VARCHAR(45)   NOT NULL,
                `estado`    ENUM('0','1') NOT NULL DEFAULT '0',
                `fecha`     DATETIME      NOT NULL,
                `roles_id`  INT(11)       NOT NULL,
                `clave_prov` VARCHAR(500) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `roles_permisos` (
                `id`        INT(11)       NOT NULL AUTO_INCREMENT,
                `editar`    ENUM('0','1') NOT NULL DEFAULT '0',
                `crear`     ENUM('0','1') NOT NULL DEFAULT '0',
                `ver`       ENUM('0','1') NOT NULL DEFAULT '0',
                `eliminar`  ENUM('0','1') NOT NULL DEFAULT '0',
                `id_admin`  INT(11)       NOT NULL,
                `id_modulo` INT(11)       NOT NULL,
                PRIMARY KEY (`id`),
                KEY `indx_id_admin` (`id_admin`) USING BTREE,
                KEY `modulo_x_mod`  (`id_modulo`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `modulos_activos` (
                `id`     INT(11)       NOT NULL AUTO_INCREMENT,
                `modulo` VARCHAR(50)   NOT NULL,
                `estado` ENUM('0','1') NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `usuarios` (
                `id`              INT(11)       NOT NULL AUTO_INCREMENT,
                `usuario`         VARCHAR(45)   NOT NULL,
                `clave`           VARCHAR(100)  CHARACTER SET utf8 NOT NULL,
                `nombre`          VARCHAR(45)   NOT NULL,
                `apellido`        VARCHAR(45)   NOT NULL,
                `telefono`        INT(11)       NOT NULL,
                `correo`          VARCHAR(45)   NOT NULL,
                `estado`          ENUM('0','1') NOT NULL DEFAULT '0',
                `fecha_registro`  DATETIME      NOT NULL,
                `clave_prov`      VARCHAR(500)  DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `departamentos` (
                `id`     INT(11)     NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(50) NOT NULL,
                `codigo` INT(11)     NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `municipios` (
                `id`                  INT(11)      NOT NULL AUTO_INCREMENT,
                `codigo_departamento` INT(11)      NOT NULL,
                `nombre_municipio`    VARCHAR(100) NOT NULL,
                `codigo_municipio`    INT(20)      NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `usuarios_direcciones` (
                `id`             INT(11)        NOT NULL AUTO_INCREMENT,
                `nombre`         VARCHAR(45)    NOT NULL,
                `telefono`       INT(11)        NOT NULL,
                `correo`         VARCHAR(45)    NOT NULL,
                `direccion`      VARCHAR(100)   NOT NULL,
                `usuarios_id`    INT(11)        NOT NULL,
                `fecha`          DATETIME       NOT NULL,
                `estado`         ENUM('1','0')  NOT NULL,
                `identificacion` VARCHAR(100)   NOT NULL,
                `departamento`   INT(11)        NOT NULL,
                `municipio`      INT(11)        NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `bancos` (
                `id`     INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(45)   NOT NULL,
                `tipo`   ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1-ahorros 2-corriente',
                `cuenta` VARCHAR(45)   NOT NULL,
                `estado` ENUM('0','1') NOT NULL DEFAULT '0',
                `fecha`  DATETIME      NOT NULL,
                `qr_img` TEXT          DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `productos` (
                `id`              INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre`          VARCHAR(45)   NOT NULL,
                `precio`          DECIMAL(10,2) NOT NULL,
                `impuesto`        DECIMAL(10,2) NOT NULL,
                `url_imagen`      VARCHAR(50)   NOT NULL,
                `estado`          ENUM('0','1') NOT NULL,
                `descripcion`     VARCHAR(100)  NOT NULL,
                `fecha_registro`  DATETIME      DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `productos_descuento` (
                `id`           INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre`       VARCHAR(45)   NOT NULL,
                `descuento`    DECIMAL(10,2) NOT NULL,
                `estado`       ENUM('0','1') NOT NULL DEFAULT '0',
                `maximo`       DECIMAL(10,2) NOT NULL,
                `minimo`       DECIMAL(10,2) NOT NULL,
                `productos_id` INT(11)       NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `ordenes_compras` (
                `id`                   INT(11)       NOT NULL AUTO_INCREMENT,
                `total_orden_compra`   DECIMAL(10,2) NOT NULL,
                `total_descuento`      DECIMAL(10,2) NOT NULL,
                `total_impuesto`       DECIMAL(10,2) NOT NULL,
                `metodo_pago`          INT(11)       NOT NULL,
                `fecha`                DATETIME      NOT NULL,
                `estado_proceso`       ENUM('0','1') NOT NULL DEFAULT '0',
                `estado_aprobacion`    ENUM('0','1') NOT NULL DEFAULT '0',
                `soporte_pago`         VARCHAR(100)  NOT NULL,
                `datos_envio`          TEXT          NOT NULL,
                `numero_orden`         VARCHAR(50)   NOT NULL,
                `id_usuario`           INT(11)       NOT NULL,
                `datos_facturacion`    TEXT          NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `ordenes_compras_detalles` (
                `id`                  INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre_producto`     VARCHAR(45)   NOT NULL,
                `precio_producto`     DECIMAL(10,2) NOT NULL,
                `impuesto_producto`   DECIMAL(10,2) NOT NULL,
                `descuento_producto`  DECIMAL(10,2) NOT NULL,
                `cantidad`            INT(11)       NOT NULL,
                `fecha`               DATETIME      NOT NULL,
                `id_producto`         INT(11)       NOT NULL,
                `ordenes_compras_id`  INT(11)       NOT NULL,
                `precio_calculado`    DECIMAL(10,2) NOT NULL,
                `orden_asociada`      VARCHAR(100)  NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `compras` (
                `id`                INT(11)       NOT NULL AUTO_INCREMENT,
                `nro_compra`        VARCHAR(20)   NOT NULL,
                `total_compra`      DECIMAL(10,2) NOT NULL,
                `total_descuento`   DECIMAL(10,2) NOT NULL,
                `total_impuesto`    DECIMAL(10,2) NOT NULL,
                `metodo_pago`       VARCHAR(45)   NOT NULL,
                `fecha_compra`      DATETIME      NOT NULL,
                `estado_envio`      ENUM('0','1') NOT NULL DEFAULT '0',
                `estado_proceso`    ENUM('0','1') NOT NULL DEFAULT '0',
                `estado_aprobacion` ENUM('0','1') NOT NULL DEFAULT '0',
                `soporte_pago`      VARCHAR(100)  NOT NULL,
                `id_usuario`        INT(11)       NOT NULL,
                `datos_envio`       TEXT          NOT NULL,
                `datos_facturacion` TEXT          NOT NULL,
                `orden_asociada`    VARCHAR(50)   NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `compras_detalles` (
                `id`               INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre`           VARCHAR(45)   NOT NULL,
                `precio`           DECIMAL(10,2) NOT NULL,
                `cantidad`         INT(11)       NOT NULL,
                `impuesto`         DECIMAL(10,2) NOT NULL,
                `descuento`        DECIMAL(10,2) NOT NULL,
                `fecha`            DATETIME      NOT NULL,
                `id_producto`      INT(11)       NOT NULL,
                `id_compra`        INT(11)       NOT NULL,
                `precio_calculado` DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (`id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `sistema` (
                `id`       INT(11)       NOT NULL AUTO_INCREMENT,
                `nombre`   VARCHAR(45)   NOT NULL,
                `valor`    TEXT          NOT NULL,
                `defecto`  TEXT          NOT NULL,
                `relacion` VARCHAR(45)   NOT NULL,
                `estado`   ENUM('0','1') NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(mysqli $db): void
    {
        $tables = [
            'compras_detalles',
            'compras',
            'ordenes_compras_detalles',
            'ordenes_compras',
            'productos_descuento',
            'productos',
            'bancos',
            'usuarios_direcciones',
            'municipios',
            'departamentos',
            'sistema',
            'usuarios',
            'modulos_activos',
            'roles_permisos',
            'admin',
            'roles',
        ];

        foreach ($tables as $table) {
            $db->query("DROP TABLE IF EXISTS `$table`");
        }
    }
}
