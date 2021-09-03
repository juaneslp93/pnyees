<?php 
@session_start();
require '../model/conexion.php';
require "../model/ssp.php";
require '../model/mdl_procesos_admin.php';
#Definición de entradas
$casos = array(
	"datosInicio",
	"notificaciones",
	"cargarDatosGrafico",
	"cargarInfoDetallesUsuarios",
	"cargarMediosPagos",
	"CrearBanco",
	"actPasarela",
	"cargarConfigGeneral",
	"cargarTitulacionEmpresarial",
	"cargarRolesAdministracion",
	"actRoles",
	"actPermiso",
	"crearRol",
	"listaRolesUsuarios",
	"crearNuevoUsuario",
	"cargarRoles",
	"editarUsuario",
	"eliminarUsuario",
	"asignarRolUsuario"
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
		
		$datos = ProcesosAdmin::datos_dashboard();

		$result = array("continue" => true, "datos"=>$datos);
		break;

	case 'notificaciones':
		
		$datos = ProcesosAdmin::datos_notificaciones();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarDatosGrafico':
		$datos = ProcesosAdmin::datos_grafico();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarInfoDetallesUsuarios':
		$id = Conexion::desencriptar($_POST["id"], 'Tbl1');
		$datos = ProcesosAdmin::datos_detalles_usuarios($id);
		$usuario = $datos["usuario"];
		$nommbre_completo = $datos["nommbre_completo"];
		$correo = $datos["correo"];
		$total_comprado = number_format($datos["total_comprado"],2,'',',');
		$total_cantidad = (($datos["total_cantidad"]>0)?$datos["total_cantidad"]:0);
		$fecha_registro = ProcesosAdmin::validar_fecha($datos["fecha_registro"]);
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
			            <img src="../assets/img/profile.jfif"
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
	case 'cargarMediosPagos':
		$html = ProcesosAdmin::cargar_medios_pago();
		$result = array("continue" => true, "html"=>$html);
		break;
	case 'CrearBanco':
		$nombre = $_POST["nombre"];
		$cuenta = $_POST["cuenta"];
		$tipo = $_POST["tipo"];
		$continue = true;

		if (empty($nombre) || empty($cuenta) || empty($tipo) ) {
			$continue = false;
			$mensaje = "El nombre, la cuenta y el tipo son obligatorios";
		}

		if (ProcesosAdmin::validar_cuenta_existe($cuenta, $tipo)) {
			$continue = false;
			$mensaje = "El numero de cuenta ya se encuentra registrada";
		}

		if ($continue) {
			$reg = ProcesosAdmin::registrar_banco($nombre, $cuenta, $tipo);
			$continue = $reg["estado"];
			if ($reg["estado"]) {
				$mensaje = '<span class="text text-success"><h1 class="h4 text-gray-900 mb-4">¡Registro exitoso!</h1></span> ['.$imagen["mensaje"].']';
			}else{
				$mensaje = $reg["mensaje"];
			}
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'actPasarela':
		$opcion =  $_POST["opcion"];
		$opcion = str_replace('check', '', $opcion);
		$opcion =  Conexion::decriptTable($opcion);
		$valor =  $_POST["valor"];
		
		$result = Conexion::editSystem("estado", $valor, 'id', $opcion);
		$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		break;
	case 'cargarConfigGeneral':
		$html = ProcesosAdmin::cargar_config_general();
		$result = array("continue" => true, "mensaje"=>"consulta realizada", "html"=>$html);
		break;
	case 'cargarTitulacionEmpresarial':
		$html = ProcesosAdmin::facturacion_titulo_empresa();
		$result = array("continue" => true, "mensaje"=>"consulta realizada", "html"=>$html);
		break;
	case 'cargarRolesAdministracion':
		$html = ProcesosAdmin::cargar_roles_y_administracion();
		$result = array("continue" => true, "mensaje"=>"consulta realizada", "html"=>$html);
		break;
	case 'actRoles':
		$opcion =  $_POST["opcion"];
		$opcion = str_replace('check', '', $opcion);
		$id =  Conexion::decriptTable($opcion);
		$valor =  $_POST["valor"];
		
		$result = ProcesosAdmin::editar_estado_rol($id, $valor);
		$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		break;
	case 'actPermiso':
		$opcion =  $_POST["opcion"];
		$opcion = str_replace('check', '', $opcion);
		$id =  Conexion::decriptTable($opcion);
		$valor =  $_POST["valor"];
		$campo =  $_POST["campo"];
		$continue = true;		
		$result = ProcesosAdmin::editar_estado_permiso($id, $valor, $campo);
		$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		break;
	case 'crearRol':
		$nombre = $_POST["nombre"];
		$ver = (($_POST["ver"])?1:0);
		$crear = (($_POST["crear"])?1:0);
		$editar = (($_POST["editar"])?1:0);
		$eliminar = (($_POST["eliminar"])?1:0);
		$continue = true;

		if (empty($nombre)) {
			$continue = false;
			$mensaje = "Por favor indique un nombre al nuevo rol ";
		}

		if($continue){
			$datos = ProcesosAdmin::registrar_rol($nombre, $ver, $crear, $editar, $eliminar);
			$continue = $datos["result"];
			$mensaje = $datos["mensaje"];
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'listaRolesUsuarios':
		$table = "admin";
		$primaryKey = "id";
		$columns  = array(			
			array('db' => 'usuario', 'dt'=>0),
			array('db' => 'nombre', 'dt'=>1),
			array('db' => 'telefono', 'dt'=>2),
			array('db' => 'correo', 'dt'=>3),
			array('db' => 'estado', 'dt'=>4, 'formatter'=>function($val, $fila){
				return '
					<div>
						'.(($val=='1')?'
							<i class="btn btn-success btn-circle btn-lg">
								<i class="fa fa-check"></i>
							</i>
						':'
							<i class="btn btn-danger btn-circle btn-lg">
								<i class="fa fa-close"></i>
							</i>
						').'
					</div>';
			}),
			array('db' => 'roles_id', 'dt'=>5, 'formatter'=>function($val, $fila){
				$nombre = ProcesosAdmin::traer_nombre_rol_dt($val);
				return '
					<div>
						'.$nombre.'
					</div>';
			}),
			array('db' => 'id', 'dt'=>6, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($val);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '
				<div class="dropdown mb-4 ">
					<button class="btn btn-primary dropdown-toggle" type="button" id="accionesMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Acciones
					</button>
					<div class="dropdown-menu animated--fade-in " aria-labelledby="accionesMenuButton" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
						<a class="dropdown-item asignarRol" href="#" data-control="'.$idEncrip.'"><i class="fa fa-users"></i> Asignar un rol</a>
						<a class="dropdown-item editarUsuario" href="#" data-control="'.$idEncrip.'"><i class="fa fa-edit"></i> Editar</a>
						<a class="dropdown-item eliminarUsuario" href="#" data-control="'.$idEncrip.'"><i class="fa fa-close"></i> Eliminar</a>
					</div>
				</div>';
			})
		);
		$conexion = Conexion::iniciar();
		$sql_details = Conexion::dataTable(KEYGEN_DATATBLE);
		$data = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, 'id != 1 ', '' );
		$conexion->close();
		break;
	case 'crearNuevoUsuario':
		$usuario = trim($_POST["usuario"]);
		$usuario = str_replace(' ', '', $usuario);
		$usuario = strtolower($usuario);
		$nombre = $_POST["nombre"];
		$telefono = $_POST["telefono"];
		$correo = $_POST["correo"];
		$continue = true;

		if (empty($nombre) || empty($usuario) || empty($correo) || empty($telefono)) {
			$continue = false;
			$mensaje = "Todos los campos son obligatorios ";
		}

		if($continue){
			$datos = ProcesosAdmin::registrar_nuevo_usuario($nombre, $usuario, $correo, $telefono);
			$continue = $datos["result"];
			$mensaje = $datos["mensaje"];
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'cargarRoles':
		$idEncrip = Conexion::formato_encript($_POST["data-control"], "des");
		$id = Conexion::decriptTable($idEncrip);
		$continue = true;
		if (!is_numeric($id)) {
			$continue = false;
			$mensaje = "Error al tomar el identificador del elemento ";
			$html = "Error al cargar los roles";
		}

		if ($continue) {
			$data = ProcesosAdmin::cargar_roles_asignacion($id);
			$continue = $data["result"];
			$mensaje = $data["mensaje"];
			$html = $data["html"];
		}
		
		$result = array("continue" => $continue, "mensaje"=>$mensaje, "html"=>$html);
		break;
	case 'editarUsuario':
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'eliminarUsuario':
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'asignarRolUsuario':
		$idRol = $_POST["selectRol"];
		$idAdmin = $_POST["id"];
		$idRol = Conexion::formato_encript($idRol, "des");
		$idRol = Conexion::decriptTable($idRol);
		$idAdmin = Conexion::formato_encript($idAdmin, "des");
		$idAdmin = Conexion::decriptTable($idAdmin);
		$continue = true;
		
		if (!is_numeric($idRol) || !is_numeric($idAdmin)) {
			$mensaje = "Error en los parametros enviados ";
			$continue = false;
		}

		if ($continue) {
			$data = ProcesosAdmin::actualizar_rol_usuario($idAdmin, $idRol);
			$continue = $data["result"];
			$mensaje = $data["mensaje"];
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