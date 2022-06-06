<?php 
/**
 * MODELO VISTAS
 */
class Vistas
{
	
	function __construct(){
		# code...
	}

	public static function menu()	{
		if ($_SESSION["SYSTEM"]["TIPO"]==="USER") {
			$datos = self::consultaSystem("relacion", "config_diseno");
            $clase = "navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion";
            if ($datos["estado"]) {
                for ($i=0; $i <count($datos["datos"]) ; $i++) {
                    $id = (int)$datos["datos"][$i]["id"];
                    if($id==20){
                        $clase = $datos["datos"][$i]["valor"];
                        break;
                    }
                }
            }
			$result = '
		        <ul class="'.$clase.'" id="accordionSidebar">

		            <!-- Divider -->
		            <hr class="sidebar-divider my-0">

		            <!-- Nav Item - Dashboard -->
		            <li class="nav-item active">
		                <a class="nav-link" href="inicio">
		                    <i class="fas fa-fw fa-tachometer-alt"></i>
		                    <span>Inicio</span></a>
		            </li>

		            <!-- Divider -->
		            <hr class="sidebar-divider">

		            <!-- Nav Item - Pages Collapse Menu -->
		            <li class="nav-item">
		                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
		                    aria-expanded="true" aria-controls="collapseTwo">
		                    <i class="fas fa-fw fa-shopping-cart"></i>
		                    <span> Mi comercio</span>
		                </a>
		                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
		                    <div class="bg-white py-2 collapse-inner rounded">
		                        <a class="collapse-item" href="'.URL_ABSOLUTA.'tienda-'.$_SESSION["SYSTEM"]["ID"].'&1">Ir a tienda</a>
		                        <a class="collapse-item" href="lista-compras">Compras</a>
		                        <a class="collapse-item" href="lista-orden-compras">Ordenes</a>
		                        <a class="collapse-item" href="reporte-general">Reporte general</a>
		                    </div>
		                </div>
		            </li>

		            <!-- Divider -->
		            <hr class="sidebar-divider d-none d-md-block">

		            <!-- Sidebar Toggler (Sidebar) -->
		            <div class="text-center d-none d-md-inline">
		                <button class="rounded-circle border-0" id="sidebarToggle"></button>
		            </div>

		        </ul>
        	';
		}else {
			$result = '';
		}

		return $result;
	}

	public static function navBar()	{
		if ($_SESSION["SYSTEM"]["TIPO"]==="USER") {
			$datos = self::consultaSystem("relacion", "config_diseno");
            $clase = "navbar navbar-expand navbar-light bg-white text-black-50 topbar mb-4 static-top shadow";
            if ($datos["estado"]) {
                for ($i=0; $i <count($datos["datos"]) ; $i++) {
                    $id = (int)$datos["datos"][$i]["id"];
                    if($id==21){
                        $clase = $datos["datos"][$i]["valor"];
                        break;
                    }
                }
            }
			$result = '
                <nav class="'.$clase.'">
                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="inicio">
                        <div class="sidebar-brand-text mx-3"><img src="'.URL_ABSOLUTA.'assets/img/icono.jfif" class="rounded mx-auto d-block img-fluid " width="80" /> </div>
                    </a>
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a href="https://wa.link/eshm1f" class="nav-link " target="_blank">
                                <i class="fa fa-whatsapp fa-lg text-green"></i>
                                <span class="badge badge-danger badge-counter">+</span>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-50 small">'.$_SESSION["SYSTEM"]["USER"].'</span>
                                <img class="img-profile rounded-circle"
                                    src="'.URL_ABSOLUTA.'assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="detalles-usuarios-'.$_SESSION["SYSTEM"]["ID"].'">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <a class="dropdown-item" href="editar-perfil">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configuración
                                </a>
                                <!--<a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Registro de actividades
                                </a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Salir
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
			';
		}else {
			$result = '';
		}
		return $result;
	}
}

?>