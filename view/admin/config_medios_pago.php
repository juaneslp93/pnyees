<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers('../', array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
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


                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4" id="contenido"></div>
                        
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <div class="modal fade" id="newBancoModal" tabindex="-1" role="dialog" aria-labelledby="newProductoMdl" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProductoMdl">Agregar un banco</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="modal-body">
                            <form class="user" id="FormRegistroBanco">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control"
                                            id="nombre" name="nombre" placeholder="Nombre del banco" required>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select name="tipo" id="tipo" class="form-control ">
                                            <option value="0">Tipo de cuenta</option>
                                            <option value="1">Ahorros</option>
                                            <option value="2">Corriente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="cuenta" id="cuenta"
                                            placeholder="Numero de cuenta" required>
                                </div>
                                <div class="form-group">
                                </div>
                                <input type="hidden" name="entrada" value="CrearBanco">
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registrar nuevo banco
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

    <?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2","system","proceso_medios_pago")); ?> 
</body>

</html>