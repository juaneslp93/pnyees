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
                        <div class="card-body" id="contenidoOrden">
                            <i class="fa fa-spinner fa-spin"></i>Cargando...
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
<?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2", "dataTables","system","orden_compra_detalle")); ?> 
  <script>
      $(document).ready(function () {
        Detalle_orden_compra.iniciarDetalleOrden('<?= $_GET["id"] ?>');
      });
  </script>
</html>