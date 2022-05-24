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
                            <span class="mr-2 d-none d-lg-inline text-white-50 small">'.$_SESSION["SYSTEM"]["USER"].'</span>
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
                            <span class="mr-2 d-none d-lg-inline text-white-50 small">'.$_SESSION["SYSTEM"]["USER"].'</span>
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
            <nav class="navbar navbar-expand navbar-light bg-gray topbar mb-4 static-top shadow">

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-left">
                    <li class="nav-item dropdown no-arrow">
                        <a class="btn btn-link text-white rounded-circle mr-3" href="../pnyees">
                            <i class="fa fa-dashboard"></i> Mi sucursal
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
                    <nav class="navbar navbar-expand navbar-light bg-gray topbar text-white mb-4 static-top shadow">

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-left">
                            <li class="nav-item dropdown no-arrow">
                                <a class="btn btn-link text-white rounded-circle mr-3" href="tienda-'.$ref.'&1">
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
                                    aria-labelledby="cotizado" id="contentCotizado">
                                    
                                </div>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                        </ul>
                        <ul class="navbar-nav ml-left">
                            <li class="nav-item dropdown no-arrow">
                                <a class="btn btn-link text-white rounded-circle mr-3" href="resumen">
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

    public static function resumenCarrito($ref='', $id='' )    {
        $carpeta = explode("/", $_SERVER["REDIRECT_URL"]);
        $carpetaActual = explode("&", $carpeta[2])[0];
        $resumen = '';
        $url = "tienda-$ref&1";
        if (($carpetaActual==="resumen") && !empty($_SESSION["CARRITO"])) {           
            if (!empty($_SESSION["CARRITO"])) {

                $resumen = '<div class="row">
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <div class="card shadow mb-12">
                                    <div class="table-responsive">
                                        <div class="card-body align-self-center">
                                            <div class="row text-center ">
                                                <div class="col-lg-12 table-striped">
                                                    <table class="table table-responsive d-table-cel" width="100%">
                                                        Resumen del carrito
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nombre</th>
                                                                <th>Cantidad M²</th>
                                                                <th>Descuento %</th>
                                                                <th>Precio unitario</th>
                                                                <th>Precio base</th>
                                                                <th>IVA %</th>
                                                                <th>Subtotal</th>
                                                                <th><i class="fa fa-trash "></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        
                ';
                $totalCompra = $t = 0;
                $totalDescuento = $totalImpuesto= 0.0;
                for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) {
                    $resumen .= '               <tr>
                                                    <td>'.++$t.'</td>
                                                    <td>'.$_SESSION["CARRITO"][$i]["nombre"].'</td>
                                                    <td>'.$_SESSION["CARRITO"][$i]["cantidad"].'</td>
                                                    <td>'.number_format($_SESSION["CARRITO"][$i]["descuento"],2,'.',',').'</td>
                                                    <td>'.number_format($_SESSION["CARRITO"][$i]["precio"],2,'.',',').'</td>
                                                    <td>'.number_format(($_SESSION["CARRITO"][$i]["precio"]*$_SESSION["CARRITO"][$i]["cantidad"]),2,'.',',').'</td>
                                                    <td>'.$_SESSION["CARRITO"][$i]["impuesto"].'</td>
                                                    <td>'.number_format($_SESSION["CARRITO"][$i]["precio_calculado"],2,'.',',').'</td>
                                                    <td><a href="javascript:procesoResumen.eliminarProducto(\''.Conexion::formato_encript($_SESSION["CARRITO"][$i]["id_producto"],'con').'\')"><i class="fa fa-trash text-danger"></i></a></td>  
                                                </tr>
                    ';
                    $totalImpuesto += $_SESSION["CARRITO"][$i]["impuesto"];
                    $totalDescuento += $_SESSION["CARRITO"][$i]["descuento"];
                    $totalCompra += $_SESSION["CARRITO"][$i]["precio_calculado"];
                }
                $resumen .= '                           
                                                    </tbody>
                                                </table>
                                                <button type="button" id="vaciar" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> Vaciar el carrito</button>
                                                <a href="'.$url.'" class="btn btn-info btn-sm"> <i class="fa fa-shopping-cart"></i> Continuar comprando</a>

                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>                          
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card shadow mb-12">
                                    <div class="card shadow mb-12">
                                        <div class="card-body align-self-center">
                                            <div class="row text-center ">
                                                <div class="col-lg-12 table-striped">
                                                    <table class="table table-responsive d-table-cel">
                                                        Detalle de compra
                                                        <tbody>
                
                                                            <tr>
                                                                <td>Total impuesto: </td>
                                                                <td>$'.number_format($totalImpuesto,2,'.',',').'</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total decscuento: </td>
                                                                <td>$'.number_format($totalDescuento,2,'.',',').'</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total compra: </td>
                                                                <td>$'.number_format($totalCompra,2,'.',',').'</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <a href="pagos" class="btn btn-success btn-sm"> <i class="fa fa-bank"></i> Ir al pago</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                ';

            }else{
                header("Location: $url ");
            }
        }else if($carpetaActual==="tienda-$ref" || $carpetaActual==="detalle-$id" || ($carpetaActual==="pagos" && !empty($_SESSION["CARRITO"]))){
            //////////////////////////////////////////////////////////////////////////////////////
            // Permitimos acceder a la tienda sólo cuado se cumple una de estas dos condiciones //
            //////////////////////////////////////////////////////////////////////////////////////
        }else{
            header("Location: $url ");
        }

        return $resumen;
    }
}

?>