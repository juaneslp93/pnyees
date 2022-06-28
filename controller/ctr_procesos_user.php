<?php 
@session_start();
require '../model/conexion.php';
require "../model/ssp.php";
require '../model/mdl_procesos_user.php';
#Definición de entradas
$casos = array(
	"datosInicio",
	"notificaciones",
	"cargarDatosGrafico",
	"cargarInfoDetallesUsuarios",
	"cargarDatosGlobales",
	"cargarDatosEdit",
	"cambiarClave",
	"editarDatosUser"
);
// entrada
$caso = '';
if (!empty($_POST)) {
	if (in_array(@$_POST["entrada"], $casos)) {
		$metodo = "post";
		$caso = @$_POST["entrada"];
	}
}else if(!empty($_GET)){
	if (in_array(@$_GET["entrada"], $casos)) {
		$metodo = 'get';
		$caso = @$_GET["entrada"];
	}
}

switch ($caso) {
	case 'datosInicio':
		
		$datos = ProcesosUser::datos_dashboard();

		$result = array("continue" => true, "datos"=>$datos);
		break;

	case 'notificaciones':
		
		$datos = ProcesosUser::datos_notificaciones();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarDatosGrafico':
		$datos = ProcesosUser::datos_grafico();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarInfoDetallesUsuarios':
		$id = Conexion::desencriptar($_POST["id"], 'Tbl1');
		$datos = ProcesosUser::datos_detalles_usuarios($id);
		$usuario = $datos["usuario"];
		$nommbre_completo = $datos["nommbre_completo"];
		$correo = $datos["correo"];
		$total_comprado = number_format($datos["total_comprado"],2,'',',');
		$total_cantidad = (($datos["total_cantidad"]>0)?$datos["total_cantidad"]:0);
		$fecha_registro = ProcesosUser::validar_fecha($datos["fecha_registro"]);
		$estado = (($datos["estado"]=='1')?'
					<i class="btn btn-success btn-circle btn-lg">
                        <i class="fa fa-check"></i> Activo
                    </i>':'
                    <i class="btn btn-danger btn-circle btn-lg">
                    	<i class="fa fa-close"></i> Eliminado
                    </i>');
		$html = '
				<div class="row">
			        <div class="col-sm-2 col-md-2">
			            <img src="'.URL_ABSOLUTA.'assets/img/profile.jfif"
			            alt="" class="img-rounded img-responsive img-thumbnail" />
			        </div>
			        <div class="col-sm-4 col-md-4">
			            <blockquote>
			                <h1> '.$usuario.'</h1> <cite title="Source Title"><i class="fa fa-user"></i> '.$nommbre_completo.' </cite>
			            </blockquote>
			            <p> <i class="fa fa-envelope"></i> '.$correo.'
			                <br /> <i class="fa fa-shopping-cart"></i> Total comprado: $'.$total_comprado.'
			                <br /> <i class="fa fa-tachometer"></i> Total Metros<sup>2</sup>: '.$total_cantidad.'
			                <br /> <i class="fa fa-calendar"></i> '.$fecha_registro.'</p>
			        </div>
			    </div>';
		$result = array("continue" => true, "html"=>$html);
		break;
	case 'cargarDatosGlobales':
		$datos = ProcesosUser::datos_grafico_global();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarDatosEdit':
		$datos = ProcesosUser::cargar_datos_perfil_editar();
		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cambiarClave':
		$clave = trim($_POST["clave"]);
		$rptClave = trim($_POST["rpt_clave"]);
		$continue = true;
		if(empty($clave) || empty($rptClave)){
			$continue = false;
			$mensaje = "Los campos son obligatorios";
		}

		if($continue){
			if($clave !== $rptClave){
				$continue = false;
				$mensaje = "Las claves no coinciden";
			}
		}

		if($continue){
			$claveSegura = Conexion::validador_clave_segura($clave);
			if(!$claveSegura[0]){
				$continue = $claveSegura[0];
				$mensaje = $claveSegura[1];
			}
		}

		if($continue){
			$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
			$datos = ProcesosUser::cambiar_clave($clave_hash);
			$continue = $datos["result"];
			$mensaje = $datos["mensaje"];
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'editarDatosUser':
		$nombre = trim($_POST["nombre"]);
		$apellido = trim($_POST["apellido"]);
		$telefono = trim($_POST["telefono"]);
		$correo = trim($_POST["correo"]);
		$continue = true;
		if(empty($nombre) || empty($apellido) || empty($telefono) || empty($correo)){
			$continue = false;
			$mensaje = "Los campos son obligatorios";
		}

		if($continue){
			$datos = ProcesosUser::cambiar_datos_user($nombre, $apellido, $telefono, $correo);
			$continue = $datos["result"];
			$mensaje = $datos["mensaje"];
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	default:
		$result = array("continue" => false, "mensaje"=>"Metodo erróneo");
		break;
}
$mostrar = 'NADA';
if ($metodo==="post") {
	$mostrar = $result;
}else if($metodo==="get"){
	$mostrar = $data;
}
echo json_encode($mostrar);

?>