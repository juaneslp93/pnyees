<?php 
@session_start();
require '../model/conexion.php';
require '../model/mdl_login.php';
#Definición de entradas
$casos = array(
	"ingresoSistema"
);
// entrada

$caso = '';
if (!empty($_POST)) {
	if (in_array($_POST["entrada"], $casos)) {
		$caso = $_POST["entrada"];
	}
}

switch ($caso) {
	case 'ingresoSistema':
		$usuario = $_POST["usuario"];
		$clave = $_POST["clave"];
		$validacion = Login::validar_usuario($usuario);
		$continue = true;
		$mensaje = '<span class="text text-success">Acceso concedido</span>';
		$url = '';
		if (!$validacion["existe"]) {
			$continue = false;
			$mensaje = '<span class="text text-warning"><h1 class="h4 text-gray-900 mb-4">¡'.$validacion["tipo"].'!</h1></span>';
		}

		if ($continue) {
			if (!Login::validar_clave($clave, $validacion["clave"])) {
				$continue = false;
				$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Contraseña Errónea, por favor verifique nuevamente!.</h1></span>';
			}
		}

		#acceso concedido
		if ($continue) {
			$url = Login::crear_sesion($validacion);			
		}

		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>$url);
		break;
	
	default:
		echo 'NADA';
		break;
}

echo json_encode($result);

?>