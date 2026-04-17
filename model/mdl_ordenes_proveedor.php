<?php
require_once __DIR__ . '/mdl_inventario.php';

class OrdenesProveedor extends Conexion
{
    private static function siguiente_numero_op(): string
    {
        $conexion = self::iniciar();
        $res      = $conexion->query("SELECT IFNULL(MAX(id), 0) + 1 AS siguiente FROM ordenes_proveedor");
        $n        = (int)($res->fetch_assoc()['siguiente'] ?? 1);
        $conexion->close();
        return 'OP-' . str_pad($n, 5, '0', STR_PAD_LEFT);
    }

    public static function crear_orden(string $proveedor, string $notas, int $idAdmin = 0): array
    {
        $numero   = self::siguiente_numero_op();
        $fecha    = date('Y-m-d H:i:s');
        $conexion = self::iniciar();
        $stmt     = $conexion->prepare(
            "INSERT INTO ordenes_proveedor (numero_orden, proveedor, notas, fecha, estado, id_admin)
             VALUES (?, ?, ?, ?, 'pendiente', ?)"
        );
        $stmt->bind_param('ssssi', $numero, $proveedor, $notas, $fecha, $idAdmin);
        $ok = $stmt->execute();
        $id = (int)$conexion->insert_id;
        $conexion->close();
        return ['ok' => $ok, 'id' => $id, 'numero_orden' => $numero];
    }

    public static function agregar_item(int $idOrden, int $idProducto, int $cantidad, float $precioCompra): array
    {
        $conexion = self::iniciar();

        $res = $conexion->query("SELECT nombre FROM productos WHERE id = $idProducto AND estado = '1' LIMIT 1");
        if ($res->num_rows === 0) {
            $conexion->close();
            return ['ok' => false, 'mensaje' => 'Producto no encontrado'];
        }
        $nombre = $res->fetch_assoc()['nombre'];

        // Verify order exists and is still pending
        $res2 = $conexion->query("SELECT id FROM ordenes_proveedor WHERE id = $idOrden AND estado = 'pendiente' LIMIT 1");
        if ($res2->num_rows === 0) {
            $conexion->close();
            return ['ok' => false, 'mensaje' => 'La orden no existe o ya fue procesada'];
        }

        $stmt = $conexion->prepare(
            "INSERT INTO ordenes_proveedor_detalles (id_orden, id_producto, nombre_producto, cantidad, precio_compra)
             VALUES (?, ?, ?, ?, ?)"
        );
        // i=id_orden, i=id_producto, s=nombre_producto, i=cantidad, d=precio_compra
        $stmt->bind_param('iisid', $idOrden, $idProducto, $nombre, $cantidad, $precioCompra);
        $ok = $stmt->execute();
        $conexion->close();
        return ['ok' => $ok, 'mensaje' => $ok ? 'Ítem agregado' : 'Error al agregar ítem'];
    }

    public static function eliminar_item(int $idItem, int $idOrden): array
    {
        $conexion = self::iniciar();
        $ok = $conexion->query("DELETE FROM ordenes_proveedor_detalles WHERE id = $idItem AND id_orden = $idOrden");
        $conexion->close();
        return ['ok' => (bool)$ok, 'mensaje' => $ok ? 'Ítem eliminado' : 'Error al eliminar ítem'];
    }

    public static function obtener_orden(int $idOrden): array
    {
        $conexion = self::iniciar();
        $res = $conexion->query(
            "SELECT op.*, IFNULL(a.nombre, 'Sistema') AS admin_nombre
             FROM ordenes_proveedor op
             LEFT JOIN admin a ON a.id = op.id_admin
             WHERE op.id = $idOrden LIMIT 1"
        );
        if ($res->num_rows === 0) {
            $conexion->close();
            return ['ok' => false, 'mensaje' => 'Orden no encontrada'];
        }
        $orden = $res->fetch_assoc();
        $orden['ok'] = true;

        $res2 = $conexion->query(
            "SELECT d.*, p.stock AS stock_actual
             FROM ordenes_proveedor_detalles d
             LEFT JOIN productos p ON p.id = d.id_producto
             WHERE d.id_orden = $idOrden"
        );
        $orden['items'] = [];
        while ($row = $res2->fetch_assoc()) {
            $orden['items'][] = $row;
        }
        $conexion->close();
        return $orden;
    }

    public static function cancelar_orden(int $idOrden): array
    {
        $conexion = self::iniciar();
        $ok = $conexion->query(
            "UPDATE ordenes_proveedor SET estado = 'cancelada' WHERE id = $idOrden AND estado = 'pendiente'"
        );
        $afectadas = $conexion->affected_rows;
        $conexion->close();
        if ($ok && $afectadas > 0) {
            return ['ok' => true, 'mensaje' => 'Orden cancelada'];
        }
        return ['ok' => false, 'mensaje' => 'No se pudo cancelar (ya fue procesada o no existe)'];
    }

    public static function recepcionar(int $idOrden, int $idAdmin = 0): array
    {
        $conexion = self::iniciar();

        $res = $conexion->query(
            "SELECT numero_orden FROM ordenes_proveedor WHERE id = $idOrden AND estado = 'pendiente' LIMIT 1"
        );
        if ($res->num_rows === 0) {
            $conexion->close();
            return ['ok' => false, 'mensaje' => 'La orden no existe o ya fue procesada'];
        }
        $numeroOrden = $res->fetch_assoc()['numero_orden'];

        $res2 = $conexion->query(
            "SELECT id_producto, cantidad FROM ordenes_proveedor_detalles WHERE id_orden = $idOrden"
        );
        if ($res2->num_rows === 0) {
            $conexion->close();
            return ['ok' => false, 'mensaje' => 'La orden no tiene ítems para recepcionar'];
        }

        while ($item = $res2->fetch_assoc()) {
            $idProducto = (int)$item['id_producto'];
            $cantidad   = (int)$item['cantidad'];
            $conexion->query("UPDATE productos SET stock = stock + $cantidad WHERE id = $idProducto");
            Inventario::registrar_movimiento(
                $idProducto, 'entrada', $cantidad,
                $numeroOrden, 'Recepción de mercancía de proveedor', $idAdmin
            );
        }

        $fechaRecepcion = date('Y-m-d H:i:s');
        $stmt = $conexion->prepare(
            "UPDATE ordenes_proveedor SET estado = 'recibida', fecha_recepcion = ? WHERE id = ?"
        );
        $stmt->bind_param('si', $fechaRecepcion, $idOrden);
        $stmt->execute();
        $conexion->close();

        return ['ok' => true, 'mensaje' => "Recepción registrada. Stock actualizado ($numeroOrden)"];
    }

    public static function productos_activos(): array
    {
        $conexion = self::iniciar();
        $res  = $conexion->query("SELECT id, nombre, stock FROM productos WHERE estado = '1' ORDER BY nombre ASC");
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        $conexion->close();
        return $rows;
    }
}
