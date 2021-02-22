<?php 
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
                        <div class="col-lg-12 mb-6">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Datos Usuario</h6>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <div class=" col-lg-12">
                                            <!-- <img src="https://source.unsplash.com/1200x300/?water" class="img-fluid"  alt=""> -->
                                            <img src="../assets/img/stone.jfif" class="img-fluid"  alt="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body" id="info-usuario" data-control="<?= $_GET["id"] ?>"></div>
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

    <?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2","system")); ?> 
    <script src="../assets/js_pnyees/usuarios_detalles.js" type="text/javascript" charset="utf-8"></script>
</body>

</html>