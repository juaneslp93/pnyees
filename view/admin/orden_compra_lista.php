<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
$ordenes        = Conexion::saber_permiso_asociado(3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers(array("fontAwesome","fonts.googleapis","sb-admin-2", "dataTables")); ?>
</head>

<body id="page-top">
    <div class="text-center align-self-center" id="carga-global">
        <div class="spinner-border" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper" style="display:none;">

        <!-- Sidebar -->
        <?= $menu ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $navbar ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lista ordenes de compra</h6>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal was-validated" id="formProcesarOrden">
                                <?php  if($ordenes["editar"]){?>
                                    <div class="float-right">
                                        <div class="form-group">
                                            <?php  if($ordenes["eliminar"]){?>
                                                <label class="control-label">Negar orden(es)
                                                    <input type="radio" id="negar-orden" name="opcion-orden" value="1" class="form-control">
                                                </label>
                                            <?php } ?>
                                                <label class="control-label">Aprobar orden(es)
                                                    <input type="radio" id="aprobar-orden" name="opcion-orden" value="2" class="form-control">
                                                </label>
                                            
                                            <input type="hidden" name="entrada" value="procesarOrdenCompra" class="form-control">
                                            <button type="submit" class="btn btn-info">Procesar selección</button>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="lista-orden-compra" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <label class="switch">
                                                        <input type="checkbox" name="selectAllSwitch" id="selectAllSwitch" value="1">
                                                        <span class="slider round"></span>
                                                    </label></th>
                                                <th>Orden</th>
                                                <th>Usuario</th>
                                                <th>Total compra</th>
                                                <th>Total descuento</th>
                                                <th>Total impuesto</th>
                                                <th>Metodo pago</th>
                                                <th>Fecha</th>
                                                <th>procesado</th>
                                                <th>Aprobado</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

</body>
<?= Scripts::footers(array("jquery","bootstrap","sb-admin-2", "dataTables","system","orden_compra_lista")); ?> 
  
</html>