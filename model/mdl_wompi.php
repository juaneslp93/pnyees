<?php
class Wompi extends Conexion
{
    function __construct() {}

    public static function config(): array
    {
        $datos = self::consultaSystem("relacion", "config_wompi");
        $cfg = [
            'public_key'  => '',
            'private_key' => '',
            'events_key'  => '',
            'modo'        => 'sandbox',
            'activo'      => '0',
        ];
        if ($datos["estado"]) {
            foreach ($datos["datos"] as $fila) {
                $cfg[$fila["nombre"]] = $fila["valor"];
            }
        }
        return $cfg;
    }

    public static function activo(): bool
    {
        $cfg = self::config();
        return $cfg['activo'] === '1' && !empty($cfg['public_key']);
    }

    public static function url_checkout(): string
    {
        return 'https://checkout.wompi.co/p/';
    }

    /**
     * Construye la URL de redirección al checkout de Wompi.
     * $referencia = numero_orden de ordenes_compras
     * $totalCentavos = total en centavos (COP)
     */
    public static function generar_url_pago(string $referencia, int $totalCentavos, string $redirectUrl): string
    {
        $cfg = self::config();
        $params = http_build_query([
            'public-key'       => $cfg['public_key'],
            'currency'         => 'COP',
            'amount-in-cents'  => $totalCentavos,
            'reference'        => $referencia,
            'redirect-url'     => $redirectUrl,
        ]);
        return self::url_checkout() . '?' . $params;
    }

    /**
     * Verifica la firma del webhook de Wompi.
     * Docs: https://docs.wompi.co/docs/colombia/pagos-por-link/#eventos
     */
    public static function verificar_firma_webhook(array $payload): bool
    {
        $cfg = self::config();
        if (empty($cfg['events_key'])) return false;

        $props       = $payload['signature']['properties'] ?? [];
        $checksum    = $payload['signature']['checksum']   ?? '';
        $timestamp   = $payload['timestamp']               ?? '';
        $data        = $payload['data']                    ?? [];
        $transaction = $data['transaction']                ?? [];

        $cadena = '';
        foreach ($props as $prop) {
            $keys  = explode('.', $prop);
            $valor = $transaction;
            foreach ($keys as $k) {
                $valor = $valor[$k] ?? '';
            }
            $cadena .= $valor;
        }
        $cadena .= $timestamp . $cfg['events_key'];

        return hash('sha256', $cadena) === $checksum;
    }

    /**
     * Crea una orden pendiente de Wompi en ordenes_compras y retorna el numero_orden.
     * Llama a Pagos::iniciar_pago_wompi() — ver mdl_pagos.php
     */
    public static function crear_orden_pendiente(): array
    {
        return Pagos::iniciar_pago_wompi();
    }

    /**
     * Aprueba una orden por su numero_orden (llamado desde el webhook).
     */
    public static function aprobar_orden_por_referencia(string $referencia): array
    {
        require_once __DIR__ . '/mdl_ordenes_compra.php';
        return OrdenesCompra::aprobar_por_referencia_wompi($referencia);
    }

    public static function guardar_config(
        string $publicKey,
        string $privateKey,
        string $eventsKey,
        string $modo,
        string $activo
    ): array {
        $conexion = self::iniciar();
        $campos = [
            'wompi_public_key'  => $publicKey,
            'wompi_private_key' => $privateKey,
            'wompi_events_key'  => $eventsKey,
            'wompi_modo'        => $modo,
            'wompi_activo'      => $activo,
        ];
        $ok = true;
        foreach ($campos as $nombre => $valor) {
            $stmt = $conexion->prepare(
                "UPDATE sistema SET valor = ? WHERE nombre = ? AND relacion = 'config_wompi'"
            );
            $stmt->bind_param('ss', $valor, $nombre);
            if (!$stmt->execute()) {
                $ok = false;
            }
        }
        $conexion->close();
        return ['result' => $ok, 'mensaje' => $ok ? 'Configuración guardada' : 'Error al guardar'];
    }
}
