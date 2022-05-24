<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers(array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
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


                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Importar usuarios</h6>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Importar Compras</h6>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Importar Productos</h6>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Importar Direcciones</h6>
                                </div>
                                <div class="card-body">

                                </div>
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

    <?= Scripts::footers(array("jquery","bootstrap","sb-admin-2","system")); ?> 
</body>

</html>