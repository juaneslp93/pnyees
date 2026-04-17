<?php

class Inventario extends Conexion
{
    /**
     * Registra un movimiento de inventario.
     * Debe llamarse DESPUÉS de que el UPDATE de stock ya se ejecutó.
     *
     * @param int    $idProducto
     * @param string $tipo       'entrada' | 'salida' | 'ajuste'
     * @param int    $cantidad   Siempre positivo; el tipo indica la dirección
     * @param string $referencia Nro de compra, orden, etc.
     * @param string $motivo     Descripción libre
     * @param int    $idAdmin    ID del admin que generó el movimiento (0 = sistema)
     */
    public static function registrar_movimiento(
        int    $idProducto,
        string $tipo,
        int    $cantidad,
        string $referencia = '',
        string $motivo     = '',
        int    $idAdmin    = 0
    ): void {
        $conexion = self::iniciar();

        // Stock actual (ya actualizado) = stock_despues
        $res          = $conexion->query("SELECT stock FROM productos WHERE id = $idProducto");
        $stockDespues = (int)($res->fetch_assoc()['stock'] ?? 0);

        // stock_antes se deduce según la dirección del movimiento
        $stockAntes = match($tipo) {
            'salida'  => $stockDespues + $cantidad,
            'entrada' => $stockDespues - $cantidad,
            default   => $stockDespues,           // ajuste: ya incluye la diferencia
        };

        $fecha = date('Y-m-d H:i:s');
        $stmt  = $conexion->prepare("
            INSERT INTO inventario_movimientos
                (id_producto, tipo, cantidad, stock_antes, stock_despues, referencia, motivo, id_admin, fecha)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'isiiissis',
            $idProducto, $tipo, $cantidad,
            $stockAntes, $stockDespues,
            $referencia, $motivo, $idAdmin, $fecha
        );
        $stmt->execute();
        $conexion->close();
    }

    /**
     * Registra un ajuste manual de stock (desde el panel admin).
     * Aplica el delta directamente al stock del producto y lo registra.
     */
    public static function ajuste_manual(
        int    $idProducto,
        int    $cantidad,
        string $motivo  = '',
        int    $idAdmin = 0
    ): array {
        $conexion = self::iniciar();

        $res        = $conexion->query("SELECT stock FROM productos WHERE id = $idProducto");
        $row        = $res->fetch_assoc();

        if (!$row) {
            $conexion->close();
            return ['estado' => false, 'mensaje' => 'Producto no encontrado'];
        }

        $stockAntes   = (int)$row['stock'];
        $stockNuevo   = max(0, $stockAntes + $cantidad);
        $tipo         = ($cantidad >= 0) ? 'entrada' : 'ajuste';
        $cantAbs      = abs($cantidad);

        $conexion->query("UPDATE productos SET stock = $stockNuevo WHERE id = $idProducto");

        $fecha = date('Y-m-d H:i:s');
        $stmt  = $conexion->prepare("
            INSERT INTO inventario_movimientos
                (id_producto, tipo, cantidad, stock_antes, stock_despues, referencia, motivo, id_admin, fecha)
            VALUES (?, ?, ?, ?, ?, '', ?, ?, ?)
        ");
        $stmt->bind_param(
            'isiiiisis',
            $idProducto, $tipo, $cantAbs,
            $stockAntes, $stockNuevo,
            $motivo, $idAdmin, $fecha
        );
        $stmt->execute();
        $conexion->close();

        return ['estado' => true, 'mensaje' => 'Ajuste registrado', 'stock_nuevo' => $stockNuevo];
    }

    /**
     * Devuelve productos cuyo stock actual es menor o igual a su stock_minimo (cuando stock_minimo > 0).
     */
    public static function productos_bajo_stock(): array
    {
        $conexion = self::iniciar();
        $sql      = "
            SELECT id, nombre, stock, stock_minimo
            FROM productos
            WHERE estado = '1'
              AND stock_minimo > 0
              AND stock <= stock_minimo
            ORDER BY stock ASC
        ";
        $consu = $conexion->query($sql);
        $rows  = [];
        while ($r = $consu->fetch_assoc()) {
            $rows[] = $r;
        }
        $conexion->close();
        return $rows;
    }

    /**
     * Devuelve el historial de movimientos de un producto.
     */
    public static function historial_producto(int $idProducto, int $limite = 50): array
    {
        $conexion = self::iniciar();
        $sql      = "
            SELECT m.id, m.tipo, m.cantidad, m.stock_antes, m.stock_despues,
                   m.referencia, m.motivo, m.fecha,
                   IFNULL(a.nombre, 'Sistema') AS admin
            FROM inventario_movimientos AS m
            LEFT JOIN admin AS a ON a.id = m.id_admin
            WHERE m.id_producto = $idProducto
            ORDER BY m.fecha DESC
            LIMIT $limite
        ";
        $consu  = $conexion->query($sql);
        $rows   = [];
        while ($r = $consu->fetch_assoc()) {
            $rows[] = $r;
        }
        $conexion->close();
        return $rows;
    }
}
