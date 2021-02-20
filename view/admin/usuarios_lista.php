<?php 
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers('../', array("fontAwesome","fonts.googleapis","sb-admin-2", "dataTables")); ?> 
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
                            <h6 class="m-0 font-weight-bold text-primary">Lista de usuarios</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="lista-usuarios" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ususario</th>
                                            <th>Correo</th>
                                            <th>Telefono</th>
                                            <th>Estado</th>
                                            <th></th>
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
<?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2", "dataTables","system")); ?> 
<script src="../assets/js_pnyees/lista_usuarios.js" type="text/javascript" ></script>  
<script>
    jQuery(document).ready(function($) {
        procesosListaUsuarios.iniciarLista();
    });
</script>
</html>