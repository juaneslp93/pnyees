<?php 
@session_start();
require '../model/conexion.php';
require '../model/mdl_catalogo.php';
#Definición de entradas
$casos = array(
	"cargarCatalogo",
	"cargarDetalle",
	"agregarProducto",
	"actualizarCot"
);
// entrada

$caso = '';
if (!empty($_POST)) {
	if (in_array($_POST["entrada"], $casos)) {
		$caso = $_POST["entrada"];
	}
}

switch ($caso) {
	case 'cargarCatalogo':
		$datos = Catalogo::cargar_catalogo();
		$result = array("continue" => true, "mensaje"=> '', "html"=>$datos);
	break;
	case 'cargarDetalle':
		$datos = Catalogo::cargar_detalle_producto($_POST["id"]);
		$result = array("continue" => true, "mensaje"=> '', "html"=>$datos);
		break;
	case 'agregarProducto':
		$cantidad = (int)$_POST["cantidad"];
		$control = $_POST["data-control"];
		$continue = true;

		if ($cantidad == 0) {
			$continue = false;
			$mensaje = "La canitdad en M<sup>2</sup> debe ser superior a cero; ";
		}

		if ($continue) {
			$datos = Catalogo::agregar_producto($cantidad, $control);
			$mensaje = $datos["mensaje"];
			$continue = $datos["result"];
		}
		
		$result = array("continue" => $continue, "mensaje"=> $mensaje, ""=>'');
		break;
	case 'actualizarCot':
		$html = '';
		if (!empty($_SESSION["CARRITO"])) {
			for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) { 
				
				$html .= '<a class="dropdown-item" href="#">
                           <img src="uploads/'.$_SESSION["CARRITO"][$i]["imagen"].'" alt="" style="width:50px;"> 
                            <b>'.$_SESSION["CARRITO"][$i]["cantidad"].'</b> M<sup>2</sup> <b>'.$_SESSION["CARRITO"][$i]["nombre"].'</b> : $'.number_format($_SESSION["CARRITO"][$i]["precio_calculado"],0,".",",").'
                        </a>';
			}
		}
		$html .= '<hr><a class="dropdown-item" href="resumen">
                   <i class="fa fa-shopping-cart"></i> Ir al resumen
                </a>';
		$result = array("continue" => true, "html"=> $html, ""=>'');
		break;
	default:
		$result = array("continue" => false, "mensaje"=> 'No existe dicho metodo '.$caso);
	break;
}

echo json_encode($result);

?>