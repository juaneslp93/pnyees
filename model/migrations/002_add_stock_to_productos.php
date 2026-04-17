<?php

class Migration_002_add_stock_to_productos extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("
            ALTER TABLE `productos`
                ADD COLUMN `stock` INT(11) NOT NULL DEFAULT 0 AFTER `impuesto`
        ");
    }

    public function down(mysqli $db): void
    {
        $db->query("ALTER TABLE `productos` DROP COLUMN `stock`");
    }
}