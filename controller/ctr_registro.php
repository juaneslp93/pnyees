<?php 
@session_start();
require '../model/conexion.php';
require '../model/mdl_registro.php';
#Definición de entradas
$casos = array(
	"registroSistema",
	"claveSeguraValidar"
);
// entrada

$caso = '';
if (!empty($_POST)) {
	if (in_array($_POST["entrada"], $casos)) {
		$caso = $_POST["entrada"];
	}
}

switch ($caso) {
	case 'registroSistema':
		$usuario = $_POST["usuario"];
		$clave = $_POST["clave"];
		$repita_clave = $_POST["repita_clave"];
		$nombre = $_POST["nombre"];
		$apellido = $_POST["apellido"];
		$correo = $_POST["correo"];
		$telefono = $_POST["telefono"];

		$validacion = Registro::validar_usuario_registro($usuario);
		$continue = true;
		$mensaje = '<span class="text text-success">Acceso concedido</span>';
		
		if (empty($usuario) || empty($clave) || empty($repita_clave) || empty($correo) || empty($telefono)) {
				$continue = false;
				$mensaje = '<span class="text text-warning"><h1 class="h4 text-gray-900 mb-4">¡Todos los campos son obligatorios, completelos por favor!</h1></span';		
		}

		if ($continue && $validacion["existe"]) {
			$continue = false;
			$mensaje = '<span class="text text-warning"><h1 class="h4 text-gray-900 mb-4">¡Este nombre de usuario ya está registrado, intente con otro por favor!</h1></span>';
		}

		if ($continue) {
			$clave_segura = Registro::validador_clave_segura($clave);
			if ($clave!==$repita_clave) {
				$continue = false;
				$mensaje = '<span class="text text-warning"><h1 class="h4 text-gray-900 mb-4">¡Las contraseñas no coinciden!</h1></span>';
			}else if(!$clave_segura[0]){
				$continue = false;
				$mensaje = '<span class="text text-warning"><h1 class="h4 text-gray-900 mb-4">¡La contraseña no es segura!. '.$clave_segura[1].'</h1></span>';
			}			
		}

		if ($continue) {
			$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
			$reg = Registro::registrar_usuario($usuario, $clave_hash, $correo, $telefono, $nombre, $apellido);
			$continue = $reg["estado"];
			if ($reg["estado"]) {
				$mensaje = '<span class="text text-success"><h1 class="h4 text-gray-900 mb-4">¡Registro exitoso!</h1></span>';
			}else{
				$mensaje = $reg["mensaje"];
			}
		}

		#acceso concedido

		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');
		break;

	case 'claveSeguraValidar':
		$clave = $_POST["clave"];
		$clave_segura = Registro::validador_clave_segura($clave);
		$continue = $clave_segura[0];
		if(!$continue){
			$continue = false;
			$mensaje = '<span class="text text-warning"><h1 class="h4 text-gray-900 mb-4">¡La contraseña no es segura!. '.$clave_segura[1].'</h1></span>';
		}
		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');
		break;
	
	default:
		echo 'NADA';
		break;
}

echo json_encode($result);

?>