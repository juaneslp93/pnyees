<?php 
include "../../controller/ctr_vistas_tienda.php";
include "../../controller/ctr_scripts.php";
$idProducto = $_GET["id"] ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?= Scripts::headers('', array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
</head>

<body id="page-top">
    <div class="text-center align-self-center" id="carga-global">
        <div class="spinner-border" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper" style="display:none;">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $navbar ?>
                <?= $navbarTienda ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <input type="hidden" id="referencia" value="<?= $idProducto ?>">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-12" id="catalogo">                            
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow text-primary m-5 " role="status">
                                    <span class="sr-only">Cargando...</span>
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

</body>
	<?= Scripts::footers('', array("jquery","bootstrap","sb-admin-2","detalle_producto", "proceso_tienda")); ?> 
</html>