<?php 
@session_start();
require '../model/conexion.php';
require '../model/mdl_catalogo.php';
#Definición de entradas
$casos = array(
	"cargarCatalogo",
	"cargarDetalle"
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
	default:
		$result = array("continue" => false, "mensaje"=> 'No existe dicho metodo '.$caso);
	break;
}

echo json_encode($result);

?>