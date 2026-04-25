<?php
@session_start();
require "../model/conexion.php";
require "../model/ssp.php";
require '../model/mdl_ordenes_proveedor.php';

$permiso = Conexion::saber_permiso_asociado(8);
if ($permiso["ver"]) {
    $casos = [
        "lista_ordenes_proveedor",
        "crear_orden_proveedor",
        "agregar_item_orden",
        "eliminar_item_orden",
        "recepcionar_orden",
        "cancelar_orden",
    ];
} else {
    $casos = [];
}

$caso   = '';
$metodo = '';
if (!empty($_POST)) {
    if (in_array(@$_POST["entrada"], $casos)) {
        $metodo = "post";
        $caso   = $_POST["entrada"];
    }
} elseif (!empty($_GET)) {
    if (in_array(@$_GET["entrada"], $casos)) {
        $metodo = "get";
        $caso   = $_GET["entrada"];
    }
}

switch ($caso) {
    case 'lista_ordenes_proveedor':
        $table      = "ordenes_proveedor";
        $primaryKey = "id";
        $columns    = [
            ['db' => 'numero_orden', 'dt' => 0],
            ['db' => 'proveedor',    'dt' => 1],
            ['db' => 'fecha',        'dt' => 2],
            ['db' => 'estado',       'dt' => 3, 'formatter' => function ($val, $fila) {
                $clase = match($val) {
                    'recibida'  => 'success',
                    'cancelada' => 'danger',
                    default     => 'warning',
                };
                return '<span class="badge badge-'.$clase.'">'.ucfirst($val).'</span>';
            }],
            ['db' => 'fecha_recepcion', 'dt' => 4, 'formatter' => function ($val, $fila) {
                return $val ?: '—';
            }],
            ['db' => 'id', 'dt' => 5, 'formatter' => function ($val, $fila) {
                $idEncrip = Conexion::encriptTable($val);
                $idEncrip = Conexion::formato_encript($idEncrip, "con");
                return '<a href="orden-proveedor-detalle-'.$idEncrip.'" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detalle
                        </a>';
            }],
        ];
        $sql_details = Conexion::dataTable(KEYGEN_DATATBLE);
        $data        = SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, '', '');
        break;

    case 'crear_orden_proveedor':
        if ($permiso["crear"]) {
            $proveedor = trim($_POST["proveedor"] ?? '');
            $notas     = trim($_POST["notas"] ?? '');
            if (empty($proveedor)) {
                $result = ['continue' => false, 'mensaje' => 'El nombre del proveedor es obligatorio'];
                break;
            }
            $r = OrdenesProveedor::crear_orden($proveedor, $notas);
            if ($r['ok']) {
                $idEncrip = Conexion::encriptTable($r['id']);
                $idEncrip = Conexion::formato_encript($idEncrip, "con");
                $result   = [
                    'continue'  => true,
                    'mensaje'   => 'Orden ' . $r['numero_orden'] . ' creada',
                    'redirect'  => 'orden-proveedor-detalle-' . $idEncrip,
                ];
            } else {
                $result = ['continue' => false, 'mensaje' => 'Error al crear la orden'];
            }
        } else {
            $result = ['continue' => false, 'mensaje' => 'Permisos insuficientes'];
        }
        break;

    case 'agregar_item_orden':
        if ($permiso["crear"]) {
            $idEncrip   = Conexion::formato_encript($_POST["id_orden"] ?? '', "des");
            $idOrden    = (int)Conexion::decriptTable($idEncrip);
            $idProducto = (int)($_POST["id_producto"] ?? 0);
            $cantidad   = (int)($_POST["cantidad"] ?? 0);
            $precio     = (float)($_POST["precio_compra"] ?? 0);

            if ($idOrden <= 0 || $idProducto <= 0 || $cantidad <= 0) {
                $result = ['continue' => false, 'mensaje' => 'Datos inválidos'];
                break;
            }
            $r      = OrdenesProveedor::agregar_item($idOrden, $idProducto, $cantidad, $precio);
            $result = ['continue' => $r['ok'], 'mensaje' => $r['mensaje']];
        } else {
            $result = ['continue' => false, 'mensaje' => 'Permisos insuficientes'];
        }
        break;

    case 'eliminar_item_orden':
        if ($permiso["eliminar"]) {
            $idItem  = (int)($_POST["id_item"] ?? 0);
            $idEncrip = Conexion::formato_encript($_POST["id_orden"] ?? '', "des");
            $idOrden = (int)Conexion::decriptTable($idEncrip);
            if ($idItem <= 0 || $idOrden <= 0) {
                $result = ['continue' => false, 'mensaje' => 'Datos inválidos'];
                break;
            }
            $r      = OrdenesProveedor::eliminar_item($idItem, $idOrden);
            $result = ['continue' => $r['ok'], 'mensaje' => $r['mensaje']];
        } else {
            $result = ['continue' => false, 'mensaje' => 'Permisos insuficientes'];
        }
        break;

    case 'recepcionar_orden':
        if ($permiso["editar"]) {
            $idEncrip = Conexion::formato_encript($_POST["id_orden"] ?? '', "des");
            $idOrden  = (int)Conexion::decriptTable($idEncrip);
            if ($idOrden <= 0) {
                $result = ['continue' => false, 'mensaje' => 'Orden no reconocida'];
                break;
            }
            $r      = OrdenesProveedor::recepcionar($idOrden);
            $result = ['continue' => $r['ok'], 'mensaje' => $r['mensaje']];
        } else {
            $result = ['continue' => false, 'mensaje' => 'Permisos insuficientes'];
        }
        break;

    case 'cancelar_orden':
        if ($permiso["eliminar"]) {
            $idEncrip = Conexion::formato_encript($_POST["id_orden"] ?? '', "des");
            $idOrden  = (int)Conexion::decriptTable($idEncrip);
            if ($idOrden <= 0) {
                $result = ['continue' => false, 'mensaje' => 'Orden no reconocida'];
                break;
            }
            $r      = OrdenesProveedor::cancelar_orden($idOrden);
            $result = ['continue' => $r['ok'], 'mensaje' => $r['mensaje']];
        } else {
            $result = ['continue' => false, 'mensaje' => 'Permisos insuficientes'];
        }
        break;

    default:
        $result = ['continue' => false, 'mensaje' => 'Método erróneo'];
        break;
}

$mostrar = 'NADA';
if ($metodo === "post") {
    $mostrar = $result;
} elseif ($metodo === "get") {
    $mostrar = $data;
}
echo json_encode($mostrar);
