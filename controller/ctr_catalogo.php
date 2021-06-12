<?php 
@session_start();
require '../model/conexion.php';
require '../model/mdl_catalogo.php';
#Definición de entradas
$casos = array(
	"cargarCatalogo",
	"cargarDetalle",
	"agregarProducto",
	"actualizarCot",
	"eliminarProducto",
	"vaciarCarrito",
	"botonesPasarela"
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
		$html = Catalogo::InfoCotActualizada();
		
		$result = array("continue" => true, "html"=> $html, ""=>'');
		break;
	case 'eliminarProducto':
		$datos = Catalogo::procesarEliminacion($_POST["id"]);
		$result = array("continue" => true, "html"=> $datos, ""=>'');
		break;
	case 'vaciarCarrito':
		Catalogo::procesarVaciarCarrito();
		$result = array("continue" => true, "html"=> '', ""=>'');
		break;
	case 'botonesPasarela':
		$html = Catalogo::cargarBotonesPago();
		$html .= Catalogo::cargarModalDirecciones();
		$result = array("continue" => true, "html"=> $html, ""=>'');
		break;

	default:
		$result = array("continue" => false, "mensaje"=> 'No existe dicho metodo '.$caso);
	break;
}

echo json_encode($result);

?>