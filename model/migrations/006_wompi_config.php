<?php

class Migration_006_wompi_config extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("
            INSERT INTO sistema (nombre, valor, defecto, relacion, estado) VALUES
            ('wompi_public_key',  '', '', 'config_wompi', '1'),
            ('wompi_private_key', '', '', 'config_wompi', '1'),
            ('wompi_events_key',  '', '', 'config_wompi', '1'),
            ('wompi_modo',        'sandbox', 'sandbox', 'config_wompi', '1'),
            ('wompi_activo',      '0', '0', 'config_wompi', '1')
        ");
    }

    public function down(mysqli $db): void
    {
        $db->query("DELETE FROM sistema WHERE relacion = 'config_wompi'");
    }
}
