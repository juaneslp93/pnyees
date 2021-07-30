<?php 
@session_start();
require '../model/conexion.php';
require '../model/mdl_pagos.php';
#Definición de entradas
$casos = array(
	"compraDepoBanc",
	"cargarDatosFacturacion",
	"selectDireccionFact",
	"cambiarDirSel",
	"cargarMunicipios",
	"registrarDireccion"
);
// entrada

$caso = '';
if (!empty($_POST)) {
	if (in_array($_POST["entrada"], $casos)) {
		$caso = $_POST["entrada"];
	}
}

switch ($caso) {
	case 'cargarDatosFacturacion':
		$datos = array("result"=>false, "mensaje"=>"Error");
		$html = '';
		$continue = false;		
		$registrarNew = (($_POST["nuevaDirSel"]==="regi")?true:false);
		if ($registrarNew) {
			$html = Pagos::generar_html_registro_direccion();
			$datos["result"] = true;
		}else if (empty($_SESSION["DATOS_FACTURACION"])) {
			$datos = Pagos::cargarDatosFacturacion();
			if ($datos["result"]) {
				// cargamos las direcciones registradas
				$html = Pagos::generarHtmlDirecciones($datos["datos"]);
			}else{
				//formulario de registro de direcciónes
				$html = Pagos::generar_html_registro_direccion();
				$datos["result"] = true;
			}
		}
		$continue = $datos["result"];

		$result = array("continue" => $continue, "mensaje"=> '', "html"=>$html);
		break;
	case 'selectDireccionFact':
		// cargar la diección seleccionada
		$id = $_POST["id"];
		$datosDireccion = Pagos::cargarDatosFacturacion($id);
		$_SESSION["DATOS_FACTURACION"] = serialize($datosDireccion);
		$result = array("continue" => true, "mensaje"=> "Dirección seleccionada", "html"=>'');
		break;
	case 'compraDepoBanc':
		$cuenta = $_POST["numero_cuenta"];
		$fecha = $_POST["fecha"];
		$soporte = $_POST["soporte_pago"];
		$continuar = true;
		$datos = '';
		if (empty($cuenta) || empty($fecha) || empty($soporte)) {
			$continuar = false;
			$mensaje = "Todos los campos son obligatorios";
		}

		if (empty($_SESSION["CARRITO"])) {
			$continuar = false;
			$mensaje = "No hay productos para procesar";
		}

		if (empty($_SESSION["TIENDA"])) {
			$continuar = false;
			$mensaje = "No hay comprador reconocible";
		}

		if (empty($_SESSION["DATOS_FACTURACION"])) {
			$continuar = false;
			$mensaje = "No hay datos de facturación";
		}

		if ($continuar) {
			if (Pagos::consultarSoportePagoExiste($soporte)) {
				$continuar = false;
				$mensaje = "Este soporte de pago ya existe";
			}
		}

		if ($continuar) {		
			$datos = Pagos::generar_orden_deposito_bancario($cuenta, $soporte, $fecha);
			$mensaje = $datos["mensaje"];
			$continuar = $datos["result"];
		}
		$result = array("continue" => $continuar, "mensaje"=> $mensaje, "html"=>$datos);
		break;
	case 'cambiarDirSel':
		unset($_SESSION["DATOS_FACTURACION"]);
		break;
	case 'cargarMunicipios':
		$codigoDepartamento = $_POST["codigo"];
		$html = conexion::cargar_municipios(0, $codigoDepartamento);
		$result = array("continue" => true, "mensaje"=> '', "html"=>$html);
		break;
	case 'registrarDireccion':
		$nombre = $_POST["nombre"]; 
		$telefono = $_POST["telefono"]; 
		$correo = $_POST["correo"]; 
		$direccion = $_POST["direccion"]; 
		$identificacion = $_POST["identificacion"]; 
		$departamento = $_POST["departamento"]; 
		$municipio = $_POST["municipio"];
		$continue = true;

		if (empty($nombre) || empty($telefono) || empty($correo) || empty($direccion) || empty($identificacion) || empty($departamento) || empty($municipio)) {
			$continue = false;
			$mensaje = "Todos los campos son obligatorios, por favor complete el formulario";
		}

		if ($continue) {
			if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
				$mensaje = "El correo suministrado no es válido. ";
				$continue = false;
			}
		}

		if ($continue) {
			$datos = Pagos::registrar_direccion($nombre, $telefono, $correo, $direccion, $identificacion, $departamento, $municipio);
			$continue = $datos["result"];
			$mensaje = $datos["mensaje"];
		}
		
		$result = array("continue" => $continue, "mensaje"=> $mensaje, "html"=>'');
		break;

	default:
		$result = array("continue" => false, "mensaje"=> 'No existe dicho metodo '.$caso);
	break;
}

echo json_encode($result);

?>