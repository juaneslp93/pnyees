<?php 
@session_start();
require '../model/conexion.php';
require "../model/ssp.php";
require '../model/mdl_procesos_admin.php';
#Definición de entradas
$administracionPermisos = Conexion::saber_permiso_asociado(6);
if($administracionPermisos["ver"]){
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
		"cargarEditarUsuario",
		"editarUsuario",
		"eliminarUsuario",
		"asignarRolUsuario",
		"actualizarRolesPermisos",
		"editarDatosUsuario",
		"editarFactData",
		"cargarDatosEditBanco",
		"editarBanco",
		"eliminarBanco",
		"cargarDatosGlobales"
	);
}else{
	$casos = array();
}
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
	case 'cargarMediosPagos':
		$html = ProcesosAdmin::cargar_medios_pago();
		$result = array("continue" => true, "html"=>$html);
		break;
	case 'CrearBanco':
		if($administracionPermisos["crear"]){
			$nombre = $_POST["nombre"];
			$cuenta = $_POST["cuenta"];
			$tipo = $_POST["tipo"];
			$continue = true;
			$imagen = array("existe"=>false);
			# procesamos la imagen
			if($_FILES["qrCharge"]["size"]>0){
				$imagen = ProcesosAdmin::procesar_imagen_pasarela($_FILES, "qrCharge");
			}
			if (empty($nombre) || empty($cuenta) || empty($tipo) ) {
				$continue = false;
				$mensaje = "El nombre, la cuenta y el tipo son obligatorios";
			}

			if (ProcesosAdmin::validar_cuenta_existe($cuenta, $tipo)) {
				$continue = false;
				$mensaje = "El numero de cuenta ya se encuentra registrada";
			}

			if ($continue) {
				$reg = ProcesosAdmin::registrar_banco($nombre, $cuenta, $tipo, (($imagen["existe"])?$imagen["url"]:''));
				$continue = $reg["estado"];
				if ($reg["estado"]) {
					$mensaje = '<span class="text text-success"><h1 class="h4 text-gray-900 mb-4">¡Registro exitoso!</h1></span> ['.$imagen["mensaje"].']';
				}else{
					$mensaje = $reg["mensaje"];
				}
			}
		}else{
			$continue = false;
			$mensaje = "Permisos insuficientes";
		}
		
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'cargarDatosEditBanco':
		$html = '';
		$mensaje = '';
		$continue = true;
		if($administracionPermisos["editar"]){
			$idBanco = Conexion::decriptTable($_POST["opcion"]);
			if(empty($idBanco)){
				$continue = false;
				$mensaje = "Falla al reconocer el banco";
			}
			if($continue){
				$datos = ProcesosAdmin::cargar_datos_banco($idBanco);
				$html = $datos["html"];
				$continue = $datos["result"];
				$mensaje = $datos["mensaje"];
			}
		}else{
			$continue = false;
			$mensaje = "Permisos insuficientes";
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje, "html"=>$html);
		break;
	case 'editarBanco':
		if($administracionPermisos["editar"]){
			$nombre = $_POST["nombre"];
			$cuenta = $_POST["cuenta"];
			$tipo = $_POST["tipo"];
			$imgOld = $_POST["img_qr"];
			$idBanco = Conexion::decriptTable($_POST["banco"]);
			$continue = true;
			$imagen = array("existe"=>true, "url"=>$imgOld);
			# procesamos la imagen
			if($_FILES["qrCharge"]["size"]>0){
				$imagen = ProcesosAdmin::procesar_imagen_pasarela($_FILES, "qrCharge", true, $imgOld);
			}
			if (empty($nombre) || empty($cuenta) || empty($tipo) ) {
				$continue = false;
				$mensaje = "El nombre, la cuenta y el tipo son obligatorios";
			}

			if($continue){
				if(!is_numeric($idBanco) || empty($idBanco)){
					$continue = false;
					$mensaje = "Identificador no válido";
				}
			}			

			if ($continue) {
				$reg = ProcesosAdmin::actualizar_banco($nombre, $cuenta, $tipo, (($imagen["existe"])?$imagen["url"]:''), $idBanco);
				$continue = $reg["estado"];
				if ($reg["estado"]) {
					$mensaje = '<span class="text text-success"><h1 class="h4 text-gray-900 mb-4">¡Actualización exitosa!</h1></span> ['.$imagen["mensaje"].']';
				}else{
					$mensaje = $reg["mensaje"];
				}
			}
		}else{
			$continue = false;
			$mensaje = "Permisos insuficientes";			
		}
		$result = array("continue" => $continue, "mensaje"=>$mensaje);
		break;
	case 'eliminarBanco':
			if($administracionPermisos["eliminar"]){
				$idBanco = Conexion::decriptTable($_POST["banco"]);
				$continue = true;

				if(!is_numeric($idBanco) || empty($idBanco)){
					$continue = false;
					$mensaje = "Identificador no válido";
				}
	
				if ($continue) {
					$reg = ProcesosAdmin::eliminar_banco($idBanco);
					$continue = $reg["estado"];
					if ($reg["estado"]) {
						$mensaje = $reg["mensaje"];
					}else{
						$mensaje = $reg["mensaje"];
					}
				}
			}else{
				$continue = false;
				$mensaje = "Permisos insuficientes";			
			}
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
			break;
	case 'actPasarela':
		if($administracionPermisos["editar"]){
			$opcion =  $_POST["opcion"];
			$opcion = str_replace('check', '', $opcion);
			$opcion =  Conexion::decriptTable($opcion);
			$valor =  $_POST["valor"];
			
			$result = Conexion::editSystem("estado", $valor, 'id', $opcion);
			$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		}else{
			$continue = false;
			$mensaje = "Permisos insuficientes";
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}
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
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["ver"]){
			$html = ProcesosAdmin::cargar_roles_y_administracion($moderadores);
		}else{
			$html = 'Sin permisos';
		}
		$result = array("continue" => true, "mensaje"=>"consulta realizada", "html"=>$html);
		break;
	case 'actRoles':
		if($administracionPermisos["editar"]){
			$opcion =  $_POST["opcion"];
			$opcion = str_replace('check', '', $opcion);
			$id =  Conexion::decriptTable($opcion);
			$valor =  $_POST["valor"];
			
			$result = ProcesosAdmin::editar_estado_rol($id, $valor);
			$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		}else{
			$continue = false;
			$mensaje = "Permisos insuficientes";
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}
		break;
	case 'actPermiso':
		if($administracionPermisos["editar"]){
			$opcion =  $_POST["opcion"];
			$opcion = str_replace('check', '', $opcion);
			$id =  Conexion::decriptTable($opcion);
			$valor =  $_POST["valor"];
			$campo =  $_POST["campo"];
			$continue = true;		
			$result = ProcesosAdmin::editar_estado_permiso($id, $valor, $campo);
			$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		}else{
			$continue = false;
			$mensaje = "Permisos insuficientes";
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}
		break;
	case 'crearRol':
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["crear"]){
			$nombre = @$_POST["nombreRol"];
			$moduloInicio = ((@$_POST["inicio"])?1:0);#inicio
			$verInicio = ((@$_POST["verInicio"])?1:0);
			$crearInicio = ((@$_POST["crearInicio"])?1:0);
			$editarInicio = ((@$_POST["editarInicio"])?1:0);
			$eliminarInicio = ((@$_POST["eliminarInicio"])?1:0);
			$moduloModeradores = ((@$_POST["moderadores"])?1:0);#moderadores
			$verModeradores = ((@$_POST["verModeradores"])?1:0);
			$crearModeradores = ((@$_POST["crearModeradores"])?1:0);
			$editarModeradores = ((@$_POST["editarModeradores"])?1:0);
			$eliminarModeradores = ((@$_POST["eliminarModeradores"])?1:0);
			$moduloClientes = ((@$_POST["clientes"])?1:0);#clientes
			$verClientes = ((@$_POST["verClientes"])?1:0);
			$crearClientes = ((@$_POST["crearClientes"])?1:0);
			$editarClientes = ((@$_POST["editarClientes"])?1:0);
			$eliminarClientes = ((@$_POST["eliminarClientes"])?1:0);
			$moduloOrdenes = ((@$_POST["ordenes"])?1:0);#ordenes
			$verOrdenes = ((@$_POST["verOrdenes"])?1:0);
			$crearOrdenes = ((@$_POST["crearOrdenes"])?1:0);
			$editarOrdenes = ((@$_POST["editarOrdenes"])?1:0);
			$eliminarOrdenes = ((@$_POST["eliminarOrdenes"])?1:0);
			$moduloCompras = ((@$_POST["compras"])?1:0);#compras
			$verCompras = ((@$_POST["verCompras"])?1:0);
			$crearCompras = ((@$_POST["crearCompras"])?1:0);
			$editarCompras = ((@$_POST["editarCompras"])?1:0);
			$eliminarCompras = ((@$_POST["eliminarCompras"])?1:0);
			$moduloProductos = ((@$_POST["productos"])?1:0);#productos
			$verProductos = ((@$_POST["verProductos"])?1:0);
			$crearProductos = ((@$_POST["crearProductos"])?1:0);
			$editarProductos = ((@$_POST["editarProductos"])?1:0);
			$eliminarProductos = ((@$_POST["eliminarProductos"])?1:0);
			$moduloAdministracion = ((@$_POST["administracion"])?1:0);#administracion
			$verAdministracion = ((@$_POST["verAdministracion"])?1:0);
			$crearAdministracion = ((@$_POST["crearAdministracion"])?1:0);
			$editarAdministracion = ((@$_POST["editarAdministracion"])?1:0);
			$eliminarAdministracion = ((@$_POST["eliminarAdministracion"])?1:0);
			$continue = true;
	
			if (empty($nombre)) {
				$continue = false;
				$mensaje = "Por favor indique un nombre al nuevo rol ";
			}
	
			$inicio = array(
					"nombre"=>"inicio",
					"inicio"=>$moduloInicio,
					"verInicio"=>$verInicio,
					"crearInicio"=>$crearInicio,
					"editarInicio"=>$editarInicio,
					"eliminarInicio"=>$eliminarInicio
			);
			$moderadores = array(
					"nombre"=>"moderadores",
					"moderadores"=>$moduloModeradores,
					"verModeradores"=>$verModeradores,
					"crearModeradores"=>$crearModeradores,
					"editarModeradores"=>$editarModeradores,
					"eliminarModeradores"=>$eliminarModeradores
			);
			$clientes = array(
					"nombre"=>"clientes",
					"clientes"=>$moduloClientes,
					"verClientes"=>$verClientes,
					"crearClientes"=>$crearClientes,
					"editarClientes"=>$editarClientes,
					"eliminarClientes"=>$eliminarClientes
			);
			$ordenes = array(
					"nombre"=>"ordenes",
					"ordenes"=>$moduloOrdenes,
					"verOrdenes"=>$verOrdenes,
					"crearOrdenes"=>$crearOrdenes,
					"editarOrdenes"=>$editarOrdenes,
					"eliminarOrdenes"=>$eliminarOrdenes
			);
			$compras = array(
					"nombre"=>"compras",
					"compras"=>$moduloCompras,
					"verCompras"=>$verCompras,
					"crearCompras"=>$crearCompras,
					"editarCompras"=>$editarCompras,
					"eliminarCompras"=>$eliminarCompras
			);
			$productos = array(
					"nombre"=>"productos",
					"productos"=>$moduloProductos,
					"verProductos"=>$verProductos,
					"crearProductos"=>$crearProductos,
					"editarProductos"=>$editarProductos,
					"eliminarProductos"=>$eliminarProductos
			);
			$administracion = array(
					"nombre"=>"administracion",
					"administracion"=>$moduloAdministracion,
					"verAdministracion"=>$verAdministracion,
					"crearAdministracion"=>$crearAdministracion,
					"editarAdministracion"=>$editarAdministracion,
					"eliminarAdministracion"=>$eliminarAdministracion
			);
	
			if($continue){
				$datos = ProcesosAdmin::registrar_rol($nombre, array($inicio, $moderadores, $clientes, $ordenes, $compras, $productos, $administracion));
				$continue = $datos["result"];
				$mensaje = $datos["mensaje"];
			}
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene suficientes permisos");
		}
		
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
				$moderadores    = Conexion::saber_permiso_asociado(2);
				return '
				<div class="dropdown mb-4 ">
					<button class="btn btn-primary dropdown-toggle" type="button" id="accionesMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Acciones
					</button>
					<div class="dropdown-menu animated--fade-in " aria-labelledby="accionesMenuButton" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
						'.(($moderadores["editar"])?'
							<a class="dropdown-item asignarRol" href="#" data-control="'.$idEncrip.'"><i class="fa fa-users"></i> Asignar un rol</a>
							<a class="dropdown-item editarUsuario" href="#" data-control="'.$idEncrip.'"><i class="fa fa-edit"></i> Editar</a>
						':'').'
						'.(($moderadores["eliminar"])?'						
							<a class="dropdown-item eliminarUsuario" href="#" data-control="'.$idEncrip.'"><i class="fa fa-close"></i> Eliminar</a>
						':'').'
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
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["crear"]){
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
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene suficientes permisos");
		}
		
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
	case 'cargarEditarUsuario':
		$idEncrip = Conexion::formato_encript($_POST["data-control"], "des");
		$id = Conexion::decriptTable($idEncrip);
		$continue = true;
		if (!is_numeric($id)) {
			$continue = false;
			$mensaje = "Error al tomar el identificador del elemento ";
			$html = "No hay un identificador válido";
		}

		if ($continue) {
			$data = ProcesosAdmin::cargar_datos_usuario_admin($id);
			$continue = $data["result"];
			$mensaje = $data["mensaje"];
			$html = $data["html"];
		}
		
		$result = array("continue" => $continue, "mensaje"=>$mensaje, "html"=>$html);
		break;
	case 'editarUsuario':
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["editar"]){
			$opcion =  $_POST["opcion"];
			$opcion = str_replace('check', '', $opcion);
			$id =  Conexion::decriptTable($opcion);
			$valor =  $_POST["valor"];
			$campo =  $_POST["campo"];
			$continue = true;		
			// $result = ProcesosAdmin::editar_usuario_admin($id, $valor, $campo);
			$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene suficientes permisos");
		}
		
		break;
	case 'eliminarUsuario':
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["editar"]){
			$idEncrip = Conexion::formato_encript($_POST["data-control"], "des");
			$id = Conexion::decriptTable($idEncrip);
			$continue = true;
			if (!is_numeric($id) || $id==1) {
				$continue = false;
				$mensaje = "Error al tomar el identificador del elemento ";
			}

			if ($continue) {
				$data = ProcesosAdmin::eliminar_usuario_admin($id);
				$continue = $data["result"];
				$mensaje = $data["mensaje"];
			}
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene permisos");
		}
		break;
	case 'asignarRolUsuario':
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["editar"]){
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
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene suficientes permisos");
		}
		
		break;
	case 'actualizarRolesPermisos':
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["editar"]){
			$post = @$_POST;
			$data = ProcesosAdmin::proceso_actualizacion_roles($post);
			$continue = $data["result"];
			$mensaje = $data["mensaje"];
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene permisos");
		}
		
		break;
	case 'editarDatosUsuario':
		$moderadores    = Conexion::saber_permiso_asociado(2);
		if($moderadores["editar"]){
			$idUser = $_POST["id"];
			$idUser = Conexion::formato_encript($idUser, "des");
			$idUser = Conexion::decriptTable($idUser);
			$nombre = $_POST["nombre"];
			$correo = $_POST["correo"];
			$telefono = $_POST["telefono"];
			$continue = true;
			if(!is_numeric($idUser)){
				$continue = false;
				$mensaje = "No hay un identificador válido";
			}

			if($continue){
				if(empty($nombre) || empty($correo) || empty($telefono)){
					$continue = false;
					$mensaje = "No se permiten campos vacíos";
				}
			}

			if($continue){
				$datos = ProcesosAdmin::editar_datos_usuario($idUser, $nombre, $correo, $telefono);
				$continue = $datos["result"];
				$mensaje = $datos["mensaje"];
			}
			$result = array("continue" => $continue, "mensaje"=>$mensaje);
		}else{
			$result = array("continue" => false, "mensaje"=>"No tiene permisos");
		}
		break;
	case 'editarFactData':
		$nombre = $_POST["nombre_empresa"];
		$nit = $_POST["nit_empresa"];
		$contacto = $_POST["contacto"];
		$direccion = $_POST["direccion"];
		$correo = $_POST["correo"];
		$continue = true;
		if(empty($nombre) || empty($nit) || empty($contacto) || empty($direccion) || empty($correo)){
			$continue = false;
			$mensaje = "Todos los campos son obligatorios";
		}

		if($continue){
			$result = Conexion::editSystem("valor", $nombre, 'nombre', "nombre empresa");
			if($result["estado"]){
				$result = Conexion::editSystem("valor", $nit, 'nombre', "nit empresa");
			}
			if($result["estado"]){
				$result = Conexion::editSystem("valor", $contacto, 'nombre', "contacto");
			}
			if($result["estado"]){
				$result = Conexion::editSystem("valor", $direccion, 'nombre', "direccion");
			}
			$result = array("continue" => $result["estado"], "mensaje"=>$result["mensaje"]);
		}
		break;
	case 'cargarDatosGlobales':
		$datos = ProcesosAdmin::datos_grafico_global();

		$result = array("continue" => true, "datos"=>$datos);
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