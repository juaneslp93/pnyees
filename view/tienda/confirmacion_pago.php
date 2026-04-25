<?php
include "../../controller/ctr_vistas_tienda.php";
include "../../controller/ctr_scripts.php";

require_once "../../model/mdl_wompi.php";

$estado    = $_GET['estado']    ?? '';
$referencia = $_GET['referencia'] ?? '';
$idOrden   = $_GET['id']        ?? '';

$icono   = 'fa-check-circle text-success';
$titulo  = '¡Pago recibido!';
$mensaje = 'Tu orden <strong>' . htmlspecialchars($referencia) . '</strong> fue aprobada. Pronto recibirás un correo de confirmación.';

if ($estado === 'DECLINED' || $estado === 'ERROR') {
    $icono   = 'fa-times-circle text-danger';
    $titulo  = 'Pago no completado';
    $mensaje = 'El pago de la orden <strong>' . htmlspecialchars($referencia) . '</strong> fue rechazado o presentó un error. Puedes intentarlo de nuevo.';
} elseif ($estado === 'PENDING' || $estado === 'VOIDED') {
    $icono   = 'fa-clock text-warning';
    $titulo  = 'Pago pendiente';
    $mensaje = 'El pago de la orden <strong>' . htmlspecialchars($referencia) . '</strong> está en proceso. Te notificaremos cuando sea confirmado.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?= Scripts::headers(["fontAwesome","fonts.googleapis","sb-admin-2"]); ?>
</head>
<body id="page-top">
    <div class="text-center align-self-center" id="carga-global">
        <div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div>
    </div>
    <div id="wrapper" style="display:none;">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $navbar ?>
                <?= $navbarTienda ?>
                <div class="container-fluid">
                    <div class="row justify-content-center mt-5">
                        <div class="col-lg-6 col-md-8 col-sm-12 text-center">
                            <div class="card shadow mb-4">
                                <div class="card-body py-5">
                                    <i class="fas fa-4x <?= $icono ?> mb-4"></i>
                                    <h2 class="font-weight-bold"><?= $titulo ?></h2>
                                    <p class="lead mt-3"><?= $mensaje ?></p>
                                    <hr>
                                    <a href="user/lista-orden-compras" class="btn btn-primary mt-3">
                                        <i class="fas fa-list"></i> Ver mis órdenes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Scripts::footers(["jquery","bootstrap","sb-admin-2"]); ?>
</body>
</html>
