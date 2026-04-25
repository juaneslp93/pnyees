<?php

class Migration_007_modulo_ordenes_proveedor extends Migration
{
    public function up(mysqli $db): void
    {
        // Registrar el módulo con ID explícito para que los controladores
        // puedan referenciarlo por número consistentemente.
        $db->query("INSERT INTO modulos_activos (id, modulo, estado) VALUES (8, 'ordenes_proveedor', '1')");

        // Dar permisos completos a todos los roles existentes que ya
        // tienen acceso al módulo de compras (id_modulo=4), asegurando
        // que quienes ya administraban compras puedan ver órdenes a proveedor.
        $db->query("
            INSERT INTO roles_permisos (editar, crear, ver, eliminar, id_admin, id_modulo)
            SELECT
                rp.editar,
                rp.crear,
                rp.ver,
                rp.eliminar,
                rp.id_admin,
                8
            FROM roles_permisos AS rp
            WHERE rp.id_modulo = 4
        ");
    }

    public function down(mysqli $db): void
    {
        $db->query("DELETE FROM roles_permisos WHERE id_modulo = 8");
        $db->query("DELETE FROM modulos_activos WHERE id = 8");
    }
}
