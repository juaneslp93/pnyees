<?php
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
$permiso = Conexion::saber_permiso_asociado(8);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?= Scripts::headers(["fontAwesome","fonts.googleapis","sb-admin-2","dataTables"]); ?>
</head>
<body id="page-top">
    <div class="text-center align-self-center" id="carga-global">
        <div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div>
    </div>
    <div id="wrapper" style="display:none;">
        <?= $menu ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $navbar ?>
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Órdenes de compra a proveedores</h6>
                            <?php if ($permiso["crear"]): ?>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#nuevaOrdenModal">
                                <i class="fas fa-plus-square mr-1"></i> Nueva orden
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="lista-ordenes-proveedor" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>N° Orden</th>
                                            <th>Proveedor</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Fecha recepción</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
</body>

<!-- Modal nueva orden -->
<div class="modal fade" id="nuevaOrdenModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva orden de compra a proveedor</h5>
                <button class="close" type="button" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaOrden">
                    <div class="form-group">
                        <input type="text" class="form-control" name="proveedor" placeholder="Nombre del proveedor" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="notas" rows="3" placeholder="Notas (opcional)"></textarea>
                    </div>
                    <input type="hidden" name="entrada" value="crear_orden_proveedor">
                    <button type="submit" class="btn btn-primary btn-block">Crear orden</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?= Scripts::footers(["jquery","bootstrap","sb-admin-2","dataTables","system","orden_proveedor"]); ?>
</html>
