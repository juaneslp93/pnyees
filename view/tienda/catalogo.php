<?php 
include "../../controller/ctr_vistas_tienda.php";
include "../../controller/ctr_scripts.php";
/*$_SESSION["TIENDA"]["ID"] = $_GET["id"];
$_SESSION["TIENDA"]["COMPRADOR"] = Vistas::reconocer_comprador($_GET["id"]);*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?= Scripts::headers('', array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?= $navbar ?>
                <?= $navbarTienda ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-12" id="catalogo">
                            
                	       Cargando... <span class="fa fa-spinner fa-spin"></span>
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
	<?= Scripts::footers('', array("jquery","bootstrap","sb-admin-2","catalogo", "proceso_tienda")); ?> 
</html>