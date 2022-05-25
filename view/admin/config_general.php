<?php 
include "../../controller/ctr_validacionesIn.php";
include "../../controller/ctr_vistas_admin.php";
include "../../controller/ctr_scripts.php";
$cadena = " frase frase frase ";
$cadena_formateada = trim($cadena);
$cadena_formateada = str_replace(' ', '', $cadena_formateada);
$moderadoresPermisos    = Conexion::saber_permiso_asociado(2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers( array("fontAwesome","fonts.googleapis","sb-admin-2", "dataTables")); ?> 
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
                        <div class="col-lg-12 mb-4" >
                            <div class="card">
                                <div class="card-header">
                                    Administración del sistema
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
                                            <a class="nav-link" id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab" aria-controls="usuarios" aria-selected="false">Moderadores</a>
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
                                                <?php if($moderadoresPermisos["crear"]){ ?>
                                                    <div> 
                                                        <a class="btn btn-success fa-pull-right" href="#" data-toggle="modal" data-target="#nuevoUsuarioModal">
                                                            <i class="fas fa-user-plus fa-sm fa-fw mr-2"></i>
                                                            Nuevo usuario
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                
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
                    <h5 class="modal-title" id="rolMdl">Crear rol</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="form-horizontal was-validated" name="formNuevoRol"id="formNuevoRol" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="nombreRol" id="nombreRol" placeholder="Nombre del rol" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered">
                                    <thead>Permisos</thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3">Inicio</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="inicio" data-control="inicio" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verInicio" data-control="verInicio" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearInicio" data-control="crearInicio" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarInicio" data-control="editarInicio" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarInicio" data-control="eliminarInicio" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th colspan="3">Moderades</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="moderadores" data-control="moderadores" value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verModeradores" data-control="verModeradores" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearModeradores" data-control="crearModeradores" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarModeradores" data-control="editarModeradores" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarModeradores" data-control="eliminarModeradores" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th colspan="3">Clientes</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="clientes" data-control="clientes" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verClientes" data-control="verClientes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearClientes" data-control="crearClientes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarClientes" data-control="editarClientes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarClientes" data-control="eliminarClientes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th colspan="3">Ordenes de compras</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="ordenes" data-control="ordenes" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verOrdenes" data-control="verOrdenes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearOrdenes" data-control="crearOrdenes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarOrdenes" data-control="editarOrdenes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarOrdenes" data-control="eliminarOrdenes" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th colspan="3">Compras</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="compras" data-control="compras" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verCompras" data-control="verCompras" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearCompras" data-control="crearCompras" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarCompras" data-control="editarCompras" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarCompras" data-control="eliminarCompras" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th colspan="3">Productos y servicios</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="productos" data-control="productos" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verProductos" data-control="verProductos" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearProductos" data-control="crearProductos" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarProductos" data-control="editarProductos" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarProductos" data-control="eliminarProductos" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th colspan="3">Administración y configuraciones</th>
                                            <td colspan="1">
                                            <label class="switch ">
                                                    <input type="checkbox" name="administracion" data-control="administracion" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ver</th>
                                            <th>Crear</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="verAdministracion" data-control="verAdministracion" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="crearAdministracion" data-control="crearAdministracion" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="editarAdministracion" data-control="editarAdministracion" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch ">
                                                    <input type="checkbox" name="eliminarAdministracion" data-control="eliminarAdministracion" value="1">
                                                    <span class="slider round" ></span>
                                                </label>
                                            </td> 
                                        </tr>
                                    </tbody>
                                </table> 
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
                <form class="form-horizontal was-validated" name="formNuevoUsuario"id="formNuevoUsuario" method="post">
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
    <?= Scripts::footers( array("jquery","bootstrap","sb-admin-2","system","config-general", "dataTables")); ?> 
</body>

</html>