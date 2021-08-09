<?php 
/**
 * MODELO VISTAS
 */
class VistasAdmin
{
	
	function __construct(){
		# code...
	}

	public static function menu()	{
		if ($_SESSION["SYSTEM"]["TIPO"]==="MASTER"||$_SESSION["SYSTEM"]["TIPO"]==="ADMIN") {

			$result = '
		        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

		            <!-- Sidebar - Brand -->
		            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
		                <div class="sidebar-brand-text mx-3">PNYEES </div>
		            </a>

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

		            <!-- Heading -->
		            <div class="sidebar-heading">
		                Gestión
		            </div>

		            <!-- Nav Item - Pages Collapse Menu -->
		            <li class="nav-item">
		                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse1"
		                    aria-expanded="true" aria-controls="collapse1">
		                    <i class="fas fa-fw fa-user"></i>
		                    <span>Usuarios</span>
		                </a>
		                <div id="collapse1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
		                    <div class="bg-white py-2 collapse-inner rounded">
		                        <h6 class="collapse-header">Gestión de usuarios:</h6>
		                        <a class="collapse-item" href="lista-usuarios">Listar usuarios</a>
		                        <a class="collapse-item" href="reporte-usuarios">Reporte de usuarios</a>
		                    </div>
		                </div>
		            </li>

                    <!-- Nav Item - Pages Collapse Menu -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2"
                            aria-expanded="true" aria-controls="collapse2">
                            <i class="fas fa-fw fa-dollar-sign"></i>
                            <span>Compras</span>
                        </a>
                        <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Gestión de compras:</h6>
                                <a class="collapse-item" href="lista-compras">Listar Compras</a>
                                <a class="collapse-item" href="reporte-compras">Reporte de compras</a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#ordenesCompras"
                            aria-expanded="true" aria-controls="ordenesCompras">
                            <i class="fas fa-fw fa-shopping-basket"></i>
                            <span>Ordenes Compras</span>
                        </a>
                        <div id="ordenesCompras" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Gestión de ordenes de compras:</h6>
                                <a class="collapse-item" href="lista-orden-compras">Listar Ordenes Compras</a>
                                <a class="collapse-item" href="reporte-orden-compras">Reporte de Ordenes de compras</a>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse3"
                            aria-expanded="true" aria-controls="collapse3">
                            <i class="fas fa-fw fa-box-open"></i>
                            <span>Productos y servicios</span>
                        </a>
                        <div id="collapse3" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Productos y servicios:</h6>
                                <a class="collapse-item" href="lista-productos">Listar Productos</a>
                                <a class="collapse-item" href="servicios">Servicios</a>
                            </div>
                        </div>
                    </li>

		            <!-- Nav Item - Utilities Collapse Menu -->
		            <li class="nav-item">
		                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#config-collapse"
		                    aria-expanded="true" aria-controls="config-collapse">
		                    <i class="fas fa-fw fa-wrench"></i>
		                    <span>Configuración</span>
		                </a>
		                <div id="config-collapse" class="collapse" aria-labelledby="headingUtilities"
		                    data-parent="#accordionSidebar">
		                    <div class="bg-white py-2 collapse-inner rounded">
		                        <h6 class="collapse-header">Configuración de sistema</h6>
		                        <a class="collapse-item" href="config-general">General</a>
		                        <a class="collapse-item" href="config-diseno">Diseño</a>
                                <a class="collapse-item" href="medios-pago">Medios de pago</a>
		                        <a class="collapse-item" href="importar">Importar</a>
		                    </div>
		                </div>
		            </li>

		            <!-- Divider -->
		            <hr class="sidebar-divider">

		            <!-- Sidebar Toggler (Sidebar) -->
		            <div class="text-center d-none d-md-inline">
		                <button class="rounded-circle border-0" id="sidebarToggle"></button>
		            </div>
		        </ul>
        	';
		}else if($_SESSION["SYSTEM"]["TIPO"]==="USER"){
			$result = '';
		}

		return $result;
	}

	public static function navBar()	{
		if ($_SESSION["SYSTEM"]["TIPO"]==="MASTER"||$_SESSION["SYSTEM"]["TIPO"]==="ADMIN") {
			$result = '
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" id="notif-bel"><i class="fa fa-spinner fa-spin"></i></span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown" id="notif-content">
                                <a class="dropdown-item d-flex align-items-center" href="javascript:">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fa fa-spinner fa-spin text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500"></div>
                                        <span class="font-weight-bold">Cargando...</span>
                                    </div>
                                </a>
                                
                                
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
			';
		}else if($_SESSION["SYSTEM"]["TIPO"]==="USER"){
			$result = '';
		}
		return $result;
	}
}

?>