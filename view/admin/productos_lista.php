<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers('../', array("fontAwesome","fonts.googleapis","sb-admin-2", "dataTables")); ?> 
    <style type="text/css" media="screen">
        div::selection {
        background: #fff;
        color: black;
    }

    /* Firefox */
    div::-moz-selection {
        background: #fff;
        color: black;
    }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

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
                            <h6 class="m-0 font-weight-bold text-primary">Lista de productos <a class="float-right btn btn-success btn-small" href="#" data-toggle="modal" data-target="#newProductoModal">
                                    <i class="fas fa-plus-square  fa-sm fa-fw mr-2 text-white-400"></i>
                                    Nuevo producto
                                </a></h6>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="lista-productos" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="all">Producto</th>
                                            <th>Descripción</th>
                                            <th>Precio</th>
                                            <th>Impuesto</th>
                                            <th>Archivo</th>
                                            <th>Estado</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
<div class="modal fade" id="newProductoModal" tabindex="-1" role="dialog" aria-labelledby="newProductoMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProductoMdl">Agregar un producto</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="modal-body">
                        <form class="user" id="FormRegistroProducto" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="nombre" id="nombre"
                                        placeholder="Nombre del producto" required>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="number" class="form-control form-control-user"
                                        id="precio" name="precio" placeholder="Precio Metro cuadrado" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="number" step="any" class="form-control form-control-user" name="impuesto" id="impuesto" placeholder="Impuesto %" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user"
                                        id="descripcion" name="descripcion" placeholder="Descripción" >
                                </div>
                                <div class="col-sm-6">
                                    <input type="file" class="form-control form-control-user" id="imagenProducto" name="imagenProducto" placeholder="Cargar imagen" accept="image/*" >
                                </div>
                            </div>
                            <div class="form-group">
                            </div>
                            <input type="hidden" name="entrada" value="crear_producto">
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Registrar nuevo producto
                            </button>
                            <hr>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="descuentosModal" tabindex="-1" role="dialog" aria-labelledby="descuentosMdl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descuentosMdl">Gestionar descuentos</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="modal-body " id="desFormMdl">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2", "dataTables","system", "productos_lista")); ?> 
</html>