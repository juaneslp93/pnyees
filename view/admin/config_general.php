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
                        <div class="col-lg-12 mb-4" >
                            <div class="card">
                                <div class="card-header">
                                    Configuración general
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="ConfigTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="sistema-tab" data-toggle="tab" href="#sistema" role="tab" aria-controls="sistema" aria-selected="true">Sistema</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="facturacion-tab" data-toggle="tab" href="#facturacion" role="tab" aria-controls="facturacion" aria-selected="false">Título empresarial</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">Roles y permisos</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="contenidoDelTab">
                                        <div class="tab-pane fade show active" id="sistema" role="tabpanel" aria-labelledby="sistema-tab">
                                            <div class="card" id="contenidoSistema"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>
                                        </div>
                                        <div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="facturacion-tab">
                                            <div class="card" id="contenidoFacturacion"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>
                                        </div>
                                        <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                                            <div class="card" id="contenidoRoles"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>
                                        </div>
                                    </div>
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
    

    <?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2","system","config-general")); ?> 
</body>

</html>