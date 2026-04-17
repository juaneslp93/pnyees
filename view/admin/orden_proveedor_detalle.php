<?php
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
require "../../model/mdl_ordenes_proveedor.php";

$permiso  = Conexion::saber_permiso_asociado(4);
$idEncrip = $_GET["id"] ?? '';
$idOrden  = (int)Conexion::decriptTable(Conexion::formato_encript($idEncrip, "des"));
$orden    = ($idOrden > 0) ? OrdenesProveedor::obtener_orden($idOrden) : ['ok' => false];
$esPendiente = ($orden['ok'] && $orden['estado'] === 'pendiente');

$productos = $esPendiente ? OrdenesProveedor::productos_activos() : [];

$estadoBadge = match($orden['estado'] ?? '') {
    'recibida'  => 'success',
    'cancelada' => 'danger',
    default     => 'warning',
};
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
        <?= $menu ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?= $navbar ?>
                <div class="container-fluid">

                <?php if (!$orden['ok']): ?>
                    <div class="alert alert-danger">Orden no encontrada. <a href="lista-ordenes-proveedor">Volver</a></div>
                <?php else: ?>

                    <!-- Cabecera de la orden -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Orden <?= htmlspecialchars($orden['numero_orden']) ?>
                                <span class="badge badge-<?= $estadoBadge ?> ml-2"><?= ucfirst($orden['estado']) ?></span>
                            </h6>
                            <a href="lista-ordenes-proveedor" class="btn btn-warning btn-sm">
                                <i class="fas fa-arrow-circle-left"></i> Volver
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Proveedor:</strong> <?= htmlspecialchars($orden['proveedor']) ?></p>
                                    <p><strong>Fecha de creación:</strong> <?= $orden['fecha'] ?></p>
                                    <?php if ($orden['fecha_recepcion']): ?>
                                    <p><strong>Fecha de recepción:</strong> <?= $orden['fecha_recepcion'] ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Notas:</strong> <?= nl2br(htmlspecialchars($orden['notas'] ?? '—')) ?></p>
                                    <p><strong>Creado por:</strong> <?= htmlspecialchars($orden['admin_nombre']) ?></p>
                                </div>
                            </div>

                            <?php if ($esPendiente && $permiso["editar"]): ?>
                            <hr>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success mr-2" id="btnRecepcionar"
                                    data-orden="<?= $idEncrip ?>"
                                    <?= empty($orden['items']) ? 'disabled title="Agregue al menos un ítem"' : '' ?>>
                                    <i class="fas fa-truck mr-1"></i> Recepcionar mercancía
                                </button>
                                <?php if ($permiso["eliminar"]): ?>
                                <button class="btn btn-danger" id="btnCancelar" data-orden="<?= $idEncrip ?>">
                                    <i class="fas fa-times mr-1"></i> Cancelar orden
                                </button>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Ítems de la orden -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Ítems de la orden</h6>
                            <?php if ($esPendiente && $permiso["crear"]): ?>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#agregarItemModal">
                                <i class="fas fa-plus"></i> Agregar ítem
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (empty($orden['items'])): ?>
                                <p class="text-muted">No hay ítems en esta orden todavía.</p>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-gradient-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio compra $</th>
                                            <th>Stock actual</th>
                                            <?php if ($esPendiente && $permiso["eliminar"]): ?>
                                            <th></th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($orden['items'] as $i => $item): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                                            <td><?= $item['cantidad'] ?></td>
                                            <td>$ <?= number_format((float)$item['precio_compra'], 2, '.', ',') ?></td>
                                            <td><?= (int)$item['stock_actual'] ?></td>
                                            <?php if ($esPendiente && $permiso["eliminar"]): ?>
                                            <td>
                                                <button class="btn btn-danger btn-sm btn-eliminar-item"
                                                    data-item="<?= $item['id'] ?>"
                                                    data-orden="<?= $idEncrip ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
</body>

<!-- Modal agregar ítem -->
<?php if ($esPendiente && $permiso["crear"]): ?>
<div class="modal fade" id="agregarItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar ítem a la orden</h5>
                <button class="close" type="button" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarItem">
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="form-control" name="id_producto" required>
                            <option value="">— Seleccione un producto —</option>
                            <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?> (stock: <?= $p['stock'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label>Cantidad</label>
                            <input type="number" min="1" class="form-control" name="cantidad" required>
                        </div>
                        <div class="col-sm-6">
                            <label>Precio de compra $</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="precio_compra" value="0">
                        </div>
                    </div>
                    <input type="hidden" name="id_orden" value="<?= $idEncrip ?>">
                    <input type="hidden" name="entrada" value="agregar_item_orden">
                    <button type="submit" class="btn btn-primary btn-block">Agregar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= Scripts::footers(["jquery","bootstrap","sb-admin-2","system","orden_proveedor"]); ?>
<script>
var idOrdenActual = '<?= $idEncrip ?>';
</script>
</html>
