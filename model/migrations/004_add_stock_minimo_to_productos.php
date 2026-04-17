<?php

class Migration_004_add_stock_minimo_to_productos extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("ALTER TABLE `productos` ADD COLUMN `stock_minimo` INT(11) NOT NULL DEFAULT 0 AFTER `stock`");
    }

    public function down(mysqli $db): void
    {
        $db->query("ALTER TABLE `productos` DROP COLUMN `stock_minimo`");
    }
}
