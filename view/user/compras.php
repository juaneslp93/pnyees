<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_user.php";
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
                            <h6 class="m-0 font-weight-bold text-primary">Lista de compras</h6>
                        </div>
                        <div class="card-body">                          
                            <div class="table-responsive">
                                <table class="table table-bordered" id="lista-compra" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="all">
                                                <label class="switch">
                                                    <input type="checkbox" name="selectAllSwitch" id="selectAllSwitch" value="1">
                                                    <span class="slider round"></span>
                                                </label></th>
                                            <th>Nro Compra</th>
                                            <th>Usuario</th>
                                            <th>Total compra</th>
                                            <th>Total descuento</th>
                                            <th>Total impuesto</th>
                                            <th>Metodo pago</th>
                                            <th>Fecha</th>
                                            <th>procesado</th>
                                            <th>Aprobado</th>
                                            <th>Enviado</th>
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
<?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2", "dataTables","system-user","compra_lista")); ?> 
  
</html>