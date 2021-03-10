<?php 
/**
 * MODELO VISTAS
 */
class Vistas Extends Conexion
{
	
	function __construct(){
		# code...
	}

	public static function navBar()	{
        $sesionActiva = '<ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Invitado</span>
                            <img class="img-profile rounded-circle"
                                src="assets/img/undraw_profile.svg">
                        </a>
                    </li>
                </ul>';
        
		if (isset($_SESSION["SYSTEM"])) {

            if ($_SESSION["SYSTEM"]["TIPO"]==="MASTER"||$_SESSION["SYSTEM"]["TIPO"]==="ADMIN") {
                $sesionActiva = '<ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">'.$_SESSION["SYSTEM"]["USER"].'</span>
                            <img class="img-profile rounded-circle"
                                src="assets/img/undraw_profile.svg">
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
                </ul>';
            }else if($_SESSION["SYSTEM"]["TIPO"]==="USER"){
                $sesionActiva = '<ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">'.$_SESSION["SYSTEM"]["USER"].'</span>
                            <img class="img-profile rounded-circle"
                                src="assets/img/undraw_profile.svg">
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
                </ul>';
            }
        }
        $result = '
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-left">
                    <li class="nav-item dropdown no-arrow">
                        <a class="btn btn-link text-black-50 rounded-circle mr-3" href="../">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                </ul>    
                '.$sesionActiva.'

            </nav>
        ';
		return $result;
	}

    public static function navBarTienda($ref='') {
        
        // if (!isset($_SESSION["SYSTEM"])) {
                $result = '
                    <nav class="navbar navbar-expand navbar-light bg-info topbar text-white mb-4 static-top shadow">

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-left">
                            <li class="nav-item dropdown no-arrow">
                                <a class="btn btn-link text-white rounded-circle mr-3" href="tienda-'.$ref.'">
                                    <i class="fa fa-home"></i> Inicio
                                </a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                        </ul>
                        <ul class="navbar-nav ml-left">

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="categorias" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-white small"><i class="fa fa-tags"></i> Categorias </span>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="categorias">
                                    <a class="dropdown-item" href="#">
                                       <i class="fa fa-tag" aria-hidden="true"></i>
                                        Categoria 1 
                                    </a>
                                    <a class="dropdown-item" href="#">
                                       <i class="fa fa-tag" aria-hidden="true"></i>
                                        Categoria 2
                                    </a>
                                    <a class="dropdown-item" href="#">
                                       <i class="fa fa-tag" aria-hidden="true"></i>
                                        Categoria 3
                                    </a>
                                </div>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>

                        </ul> 
                        <ul class="navbar-nav ml-auto">
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="cotizado" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-white small"><i class="fa fa-edit"></i> Cotizado actualmente</span>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="cotizado">
                                    <a class="dropdown-item" href="#">
                                       <i class="fa fa-box" aria-hidden="true"></i>
                                        Producto 1 : $20000
                                    </a>
                                    <a class="dropdown-item" href="#">
                                       <i class="fa fa-box" aria-hidden="true"></i>
                                        Producto 2 : $10000
                                    </a>
                                    <a class="dropdown-item" href="#">
                                       <i class="fa fa-box" aria-hidden="true"></i>
                                        Producto 3 : $10000
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fa fa-money "></i>
                                        Total : $40000
                                    </a>
                                </div>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                        </ul>
                        <ul class="navbar-nav ml-left">
                            <li class="nav-item dropdown no-arrow">
                                <a class="btn btn-link text-white rounded-circle mr-3" href="#">
                                    <i class="fa fa-shopping-cart"></i> Resumen de compra
                                </a>
                            </li>
                        
                        </ul> 

                    </nav>
                ';
        // }
        return $result;
    }

    public static function reconocer_comprador($idComprador='')    {
        $idComprador = self::desencriptar($idComprador, "Tbl1");
        $conexion = self::iniciar();
        $sql = "SELECT usuario FROM usuarios WHERE id = $idComprador ";
        $consu = $conexion->query($sql);
        $usuario = 'invitado';
        if ($consu->num_rows>0) {
            $rConsu = $consu->fetch_assoc();
            $usuario = $rConsu["usuario"];
        }
        $conexion->close();
        return $usuario;
    }
}

?>