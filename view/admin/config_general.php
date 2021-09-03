<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
$cadena = " frase frase frase ";
$cadena_formateada = trim($cadena);
$cadena_formateada = str_replace(' ', '', $cadena_formateada);

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


                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4" >
                            <div class="card">
                                <div class="card-header">
                                    Configuración del sistema
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
                                            <a class="nav-link" id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab" aria-controls="usuarios" aria-selected="false">Usuarios</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">Roles y permisos</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="contenidoDelTab">
                                        <div class="tab-pane fade show active" id="sistema" role="tabpanel" aria-labelledby="sistema-tab">
                                            <div class="card" id="contenidoSistema">
                                                <div class="d-flex justify-content-center">
                                                    <div class="spinner-grow text-primary m-5 " role="status">
                                                        <span class="sr-only">Cargando...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="facturacion-tab">
                                            <div class="card" id="contenidoFacturacion">
                                                <div class="d-flex justify-content-center">
                                                    <div class="spinner-grow text-primary m-5 " role="status">
                                                        <span class="sr-only">Cargando...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">
                                            <div class="card" id="contenidoUsuarios">
                                                <div> 
                                                <a class="btn btn-success fa-pull-right" href="#" data-toggle="modal" data-target="#nuevoUsuarioModal">
                                                    <i class="fas fa-user-plus fa-sm fa-fw mr-2"></i>
                                                    Nuevo usuario
                                                </a>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="lista-roles-usuarios" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Usuario</th>
                                                                <th>nombre</th>
                                                                <th>Teléfono</th>
                                                                <th>Correo</th>
                                                                <th>Estado</th>
                                                                <th>Rol</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                                            <div class="card" id="contenidoRoles">
                                                <div class="d-flex justify-content-center">
                                                    <div class="spinner-grow text-primary m-5 " role="status">
                                                        <span class="sr-only">Cargando...</span>
                                                    </div>
                                                </div>
                                            </div>
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
    
    <!-- rol Modal-->
    <div class="modal fade" id="rolModal" tabindex="-1" role="dialog" aria-labelledby="rolMdl" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rolMdl">Crear usuario</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="form-horizontal" name="formNuevoRol"id="formNuevoRol" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del rol" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">Permisos</div>
                            
                            <div class="col-sm-3"> 
                                <label class="form-label">  Ver
                                    <label class="switch ">
                                        <input type="checkbox" name="ver" data-control="ver" >
                                        <span class="slider round" ></span>
                                    </label>
                                </label>
                            </div>
                            <div class="col-sm-3"> 
                                <label class="form-label">  Crear 
                                    <label class="switch ">
                                        <input type="checkbox" name="crear" data-control="crear" >
                                        <span class="slider round" ></span>
                                    </label>
                                </label>
                            </div>
                            <div class="col-sm-3"> 
                                <label class="form-label">  Editar 
                                    <label class="switch ">
                                        <input type="checkbox" name="editar" data-control="editar" >
                                        <span class="slider round" ></span>
                                    </label>
                                </label>
                            </div>
                            <div class="col-sm-3"> 
                                <label class="form-label">  Eliminar 
                                    <label class="switch ">
                                        <input type="checkbox" name="eliminar" data-control="eliminar" >
                                        <span class="slider round" ></span>
                                    </label>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="crearRol" name="entrada">
                        <button class="btn btn-primary" type="submit" name="btnRegistrarRol"><i class="fa fa-users"></i> Crear</button>
                        <a class="btn btn-secondary " type="button" data-dismiss="modal" aria-label="Close">Cerrar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- usuario modal -->
    <div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="nuevoUsuarioMdl" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoUsuarioMdl">Crear nuevo usuario</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="form-horizontal" name="formNuevoUsuario"id="formNuevoUsuario" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="usuario" id="usuario" placeholder="Usuario" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="number" name="telefono" id="telefono" placeholder="Teléfono" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="mail" name="correo" id="correo" placeholder="Correo" class="form-control" required>
                        </div>               
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="crearNuevoUsuario" name="entrada">
                        <button class="btn btn-primary" type="submit" name="btnRegistrarUsuario"><i class="fa fa-user-plus"></i> Crear</button>
                        <a class="btn btn-secondary " type="button" data-dismiss="modal" aria-label="Close">Cerrar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- rol Modal-->
    <div class="modal fade" id="contentTableModal" tabindex="-1" role="dialog" aria-labelledby="contentTableMdl" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="tableActionContent">
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow text-primary m-5 " role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= Scripts::footers('../', array("jquery","bootstrap","sb-admin-2","system","config-general", "dataTables")); ?> 
</body>

</html>