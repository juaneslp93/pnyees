<?php
/**
 * Endpoint de webhook para eventos de Wompi.
 * URL: /wompi/webhook  (ver .htaccess)
 *
 * Wompi envía un POST con Content-Type: application/json.
 * Solo procesamos el evento transaction.updated con status APPROVED.
 */
require_once '../model/conexion.php';
require_once '../model/mdl_wompi.php';

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
if (empty($raw)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'empty body']);
    exit;
}

$payload = json_decode($raw, true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'invalid json']);
    exit;
}

$event = $payload['event'] ?? '';

if ($event !== 'transaction.updated') {
    http_response_code(200);
    echo json_encode(['ok' => true, 'msg' => 'event ignored']);
    exit;
}

if (!Wompi::verificar_firma_webhook($payload)) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'invalid signature']);
    exit;
}

$transaction = $payload['data']['transaction'] ?? [];
$status      = $transaction['status']    ?? '';
$referencia  = $transaction['reference'] ?? '';

if ($status !== 'APPROVED' || empty($referencia)) {
    http_response_code(200);
    echo json_encode(['ok' => true, 'msg' => 'status not approved or no reference']);
    exit;
}

$result = Wompi::aprobar_orden_por_referencia($referencia);

http_response_code(200);
echo json_encode(['ok' => $result['result'], 'msg' => $result['mensaje']]);
