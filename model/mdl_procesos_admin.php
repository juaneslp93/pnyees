<?php 
/**
 * 
 */
class ProcesosAdmin Extends Conexion
{
	
	function __construct()	{
		# code...
	}

	public static function datos_dashboard($value='')	{
		if ($_SESSION["SYSTEM"]["TIPO"]==="MASTER"|| $_SESSION["SYSTEM"]["TIPO"]==="ADMIN") {
			$conexion = self::iniciar();
			
			# total compras
			$sql = "SELECT sum(total_compra) as total_compra FROM compras WHERE estado_aprobacion='1' AND estado_proceso='1' ";
			$consu = $conexion->query($sql);
			$rConsu = $consu->fetch_assoc();
			$total_compra = (($rConsu["total_compra"]>0)?$rConsu["total_compra"]:0);


			# envíos realizados
			$sql = "SELECT id, estado_envio FROM compras WHERE estado_aprobacion='1' AND estado_proceso='1' ";
			$consu = $conexion->query($sql);
			$enviadas = 0;
			$total_envio = 0;
			while ($rConsu = $consu->fetch_assoc()) {
				// $id_envio = $rConsu["id"];
				$estado_envio = $rConsu["estado_envio"];
				$total_envio++;
				if ($estado_envio=='1') {
					$enviadas++;
				}
			}

			$enviadas_porcentaje = (($total_envio>0)?number_format(($enviadas*100)/$total_envio):0.00);
			$enviadas_porcentaje = '
			<div class="col-auto">
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">'.$enviadas_porcentaje.'%</div>
            </div>
            <div class="col">
                <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-info" role="progressbar"
                        style="width: '.$enviadas_porcentaje.'%" aria-valuenow="'.$enviadas_porcentaje.'" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
            </div>';

			# total usuarios
			$sql = "SELECT count(id) as total_usuarios FROM usuarios WHERE estado='1'";
			$consu = $conexion->query($sql);
			$rConsu = $consu->fetch_assoc();
			$total_usuarios = (($rConsu["total_usuarios"]>0)?$rConsu["total_usuarios"]:0);

			# total productos
			$sql = "SELECT count(id) as total_productos FROM productos WHERE estado='1'";
			$consu = $conexion->query($sql);
			$rConsu = $consu->fetch_assoc();
			$total_productos = (($rConsu["total_productos"]>0)?$rConsu["total_productos"]:0);
			
			$conexion->close();

			$result = array('total_compra' => $total_compra, 'envios_realizados'=>$enviadas_porcentaje, 'total_usuarios'=>$total_usuarios, 'total_productos'=>$total_productos, 'mensaje'=>'Consulta realizada');

			return $result;
		}else{
			$result = array('total_compra' => 0, 'envios_realizados'=>0.00, 'total_usuarios'=>0, 'total_productos'=>0, 'mensaje'=>'No tiene permisos para visualizar esta información');
			return $result;
		}
	}

	public static function datos_notificaciones($value='')	{
		if ($_SESSION["SYSTEM"]["TIPO"]==="MASTER"|| $_SESSION["SYSTEM"]["TIPO"]==="ADMIN") {
			$conexion = self::iniciar();
			
			# campana
			$sql = "SELECT count(id) as compras FROM compras WHERE estado_proceso='0' ";
			$consu = $conexion->query($sql);
			$rConsu = $consu->fetch_assoc();
			$compras = (($rConsu["compras"]>0)?$rConsu["compras"]:0);
			
			#contenido
			$contenido = '<h6 class="dropdown-header">
                            Compras sin procesar 
                        </h6>';
			$sql = "SELECT id, nro_compra, fecha_compra FROM compras WHERE estado_proceso='0'";
            $consu = $conexion->query($sql);
            while ($rConsu = $consu->fetch_assoc()) {
            	$id = $rConsu["id"];
            	$nro_compra = $rConsu["nro_compra"];
            	$fecha_compra = $rConsu["fecha_compra"];
            	$contenido .= '<a class="dropdown-item d-flex align-items-center" href="detalle-compra-'.self::encriptar($id, 'Zol1').'">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-shopping-cart text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">'.$fecha_compra.'</div>
                                        <span class="font-weight-bold">'.self::formato_nro_compra($nro_compra).'</span>
                                    </div>
                                </a>';
            }
                                
            $contenido .= '<a class="dropdown-item text-center small text-gray-500" href="lista-compras">Ver todos</a>';
			$conexion->close();

			$result = array('bell' => $compras, "notif_content"=>$contenido);

			return $result;
		}else{
			$result = array('bell' => 0);
			return $result;
		}
	}

	public static function datos_grafico($value='')	{

		$totalMes  = array(0,0,0,0,0,0,0,0,0,0,0,0);
		$grafica = array();
		$conexion = self::iniciar();
		$maximoVal = 0;
		$sql = "SELECT id, MONTH(fecha_compra) as mes FROM compras WHERE year(fecha_compra)='".date("Y")."'";
		$consu = $conexion->query($sql);
		while ($rConsu = $consu->fetch_assoc()) {
			$id = $rConsu["id"];
			$mes = $rConsu["mes"];

			$sql2 = "SELECT SUM((precio*cantidad)+((impuesto*precio)/100)*(cantidad)-((descuento*precio+(((impuesto*precio)/100)))/100)*(cantidad)) as total FROM compras_detalles WHERE id_compra = $id ";
			$consu2 = $conexion->query($sql2);
			while ($rConsu2 = $consu2->fetch_assoc()) {
				$totalMes1 = (($rConsu["mes"]=='1')?$totalMes[0]+$rConsu2["total"]:$totalMes[0]);
				$totalMes2 = (($rConsu["mes"]=='2')?$totalMes[1]+$rConsu2["total"]:$totalMes[1]);
				$totalMes3 = (($rConsu["mes"]=='3')?$totalMes[2]+$rConsu2["total"]:$totalMes[2]);
				$totalMes4 = (($rConsu["mes"]=='4')?$totalMes[3]+$rConsu2["total"]:$totalMes[3]);
				$totalMes5 = (($rConsu["mes"]=='5')?$totalMes[4]+$rConsu2["total"]:$totalMes[4]);
				$totalMes6 = (($rConsu["mes"]=='6')?$totalMes[5]+$rConsu2["total"]:$totalMes[5]);
				$totalMes7 = (($rConsu["mes"]=='7')?$totalMes[6]+$rConsu2["total"]:$totalMes[6]);
				$totalMes8 = (($rConsu["mes"]=='8')?$totalMes[7]+$rConsu2["total"]:$totalMes[7]);
				$totalMes9 = (($rConsu["mes"]=='9')?$totalMes[8]+$rConsu2["total"]:$totalMes[8]);
				$totalMes10 = (($rConsu["mes"]=='10')?$totalMes[9]+$rConsu2["total"]:$totalMes[9]);
				$totalMes11 = (($rConsu["mes"]=='11')?$totalMes[10]+$rConsu2["total"]:$totalMes[10]);
				$totalMes12 = (($rConsu["mes"]=='12')?$totalMes[11]+$rConsu2["total"]:$totalMes[11]);

				$campos = array(
					0=>$totalMes1,
					1=>$totalMes2,
					2=>$totalMes3,
					3=>$totalMes4,
					4=>$totalMes5,
					5=>$totalMes6,
					6=>$totalMes7,
					7=>$totalMes8,
					8=>$totalMes9,
					9=>$totalMes10,
					10=>$totalMes11,
					11=>$totalMes12
				);

				$totalMes = array_replace($totalMes, $campos);
				$maximoVal = (($maximoVal<max($campos))?max($campos):$maximoVal);
			}

		}
		$conexion->close();
		setlocale(LC_TIME, 'spanish');
		for ($i=0; $i <=11 ; $i++) { 
			$numero = $i+1;
			$fecha = DateTime::createFromFormat('!m', $numero);
			$mes = strftime("%B", $fecha->getTimestamp());
			array_push($grafica, array($mes, $totalMes[$i]));
		}

		return $grafica;
	}

	public static function datos_detalles_usuarios($idUsuario='')	{
		$conexion = Conexion::iniciar();
		$vista = self::crear_vistas('vista_detalles_usuarios');
		if ($vista["existe"]) {
			$sql = "SELECT usuario, nommbre_completo, correo, total_comprado, total_cantidad, estado, fecha_registro FROM ".$vista["vista"]." WHERE id_usuario = $idUsuario";
			$consu = $conexion->query($sql);
			$datos = $consu->fetch_array();
			$datos["existe"] = true;
		}else{
			$datos = array(
				"usuario"=> '', 
				"nommbre_completo"=> '', 
				"correo"=> '', 
				"total_comprado"=> 0, 
				"total_cantidad"=> 0, 
				"estado"=> '',
				"fecha_registro"=> '',
				"existe"=>false
			);
		}		
		$conexion->close();
		return $datos;
	}

	private static function tabla_existe($vista='')	{
		try {
			$conexion = self::iniciar();
			$sql = "SELECT * FROM $vista ";
			if ($consu = $conexion->query($sql)) {
				if ($consu->num_rows>0) {
					$result = true;
				}else{
					$result = false;
				}
				$conexion->close();
			}else{
				$result = false;
			}
			return $result;
			
		} catch (Exception $e) {
			return false;
		}
	}

	private static function crear_vistas($vista=''){
		switch ($vista) {
			case 'vista_detalles_usuarios':
				$existe = self::tabla_existe($vista);
				if(!$existe){
					$conexion = self::iniciar();
					$sql = "CREATE VIEW $vista AS 
					SELECT 
						u.id as id_usuario, 
						CONCAT(u.nombre,' ',u.apellido) as nommbre_completo, 
						u.usuario, 
						u.correo, 
						u.estado, 
						u.fecha_registro,
						(SELECT SUM((precio*cantidad)+((impuesto*precio)/100)*(cantidad)-((descuento*precio+(((impuesto*precio)/100)))/100)*(cantidad)) FROM compras_detalles as cd LEFT JOIN compras as c ON c.id = id_compra WHERE u.id = c.id_usuario) as total_comprado, 
						(SELECT sum(cantidad) FROM compras_detalles as cd LEFT JOIN compras as c ON c.id = id_compra WHERE u.id = c.id_usuario) as  total_cantidad
						 FROM usuarios as u  ";
					if ($conexion->query($sql)) {
						$existe = true;
					}else{
						$existe = false;
						$conexion->error;
					}
					$conexion->close();
				}
				$elemento =  array("existe"=>$existe, "vista"=>$vista);
				break;
			
			default:
				$elemento = array("existe"=>false, "vista"=>$vista);
				break;
		}
		return $elemento;
	}

	public static function validar_fecha($cadena='0000-00-00 00:00:00')	{
		if (($timestamp = strtotime($cadena)) === false) {
		    $fecha =  "0000/00/00 00:00:00";
		} else {
		    $fecha = date("Y/m/d H:m:s", $cadena);
		}
		return $fecha;
	}

	public static function validar_cuenta_existe($cuenta=''){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM bancos WHERE cuenta = '$cuenta' ";
		$consu = $conexion->query($sql);
		$result = false;
		if ($consu->num_rows>0) $result = true;
		
		$conexion->close();
		return $result;
	}

	public static function registrar_banco($nombre='', $cuenta='', $tipo=''){
		try {
			$conexion = self::iniciar();
			$sql = "INSERT INTO bancos (nombre, cuenta, tipo, estado, fecha) VALUES (?,?,?,?,?)";
			$sentencia = $conexion->prepare($sql);
			$sentencia->bind_param('sssss', $nombre, $cuenta, $tipo, $estado, $fecha_registro);
			$nombre = $nombre;
			$cuenta = $cuenta;
			$tipo = $tipo;
			$estado = '1';
			$fecha_registro = date('Y-m-d H:m:s');

			$result = true;
			$mensaje = '';
			$sentencia->execute();
					
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Hubo un problema al insertar los datos. ['.$e->getMessage().']</h1></span>';
		}
		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	private static function cargar_vista_bancos(){
		#bancos
		$conexion = self::iniciar();
		$sql = "SELECT id, nombre, cuenta, tipo, estado FROM bancos";
		$consu = $conexion->query($sql);
		$val = 0;
		$row =  '';

		if ($consu->num_rows>0) {
			$Drop=0;
			while ($rConsu = $consu->fetch_assoc()) {
				// BANCOS
				$Drop++;
				$row .= '	<tr>
								<td>'.$rConsu["nombre"].'</td>
								<td>'.$rConsu["cuenta"].'</td>
								<td>'.(($rConsu["tipo"]=='1')?'Ahorros':'Corriente').'</td>
								<td>
									<div class="dropdown">
									  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton'.$Drop.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									   Acciones
									  </button>
									  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$Drop.'">
									   <a class="dropdown-item" href="#"><i class="fa fa-edit"></i> Editar</a>
									   <a class="dropdown-item" href="#"><i class="fa fa-trash"></i> Eliminar</a>
									  </div>
									</div>
								</td>
							</t>
				';
			}
		}else{
			$row .= '
							<tr><td colspan="4" class="text-center">Sin registros</td></tr>
				';
		}
		$pasarela = self::consultaSystem("id", "102");//Activación pagos por Deposito Bancario
		$estado = (($pasarela["estado"])?'checked':'');
		$param = 'check'.self::encriptTable("102");
		$contenido = '
                    		<div class="card">
							    <div class="card-header">
							         	

							        <a class="float-center btn btn-success btn-small" href="#" data-toggle="modal" data-target="#newBancoModal">
			                                <i class="fas fa-plus-square  fa-sm fa-fw mr-2 text-white-400"></i>
			                                Nuevo Banco
			                        </a>

									<label class="switch float-right">
									  <input type="checkbox" '.$estado.' name="'.$param.'" onchange="javascript:procesoMediosPagos.actDesBtn(\''.$param.'\', this)">
									  <span class="slider round"></span>
									</label>
							    </div>

							    <div  class="collapse show" >
 									<div class="card-body">
			                        	<div class="table-responsive">
			                            <table class="table table-bordered" width="100%" cellspacing="0">
			                            	Lista de bancos registrados
											<thead>
												<tr>
													<th>Nombre</th>
													<th>Cuenta</th>
													<th>Tipo</th>
													<th>Estado</th>											
												</tr>
											</thead>
								
											<tbody>
											'.$row.'
										</tbody>
									</table>
								</div>
		';
		$conexion->close();

		return $contenido;
	}

	public static function cargar_medios_pago()	{
		$contenido = self::cargar_vista_bancos();

		# integraciones
		$pasarela = self::consultaSystem("relacion", "metodo_pago");
		$contenido .= '<div class="card shadow mb-4">
                        
                        <div class="card-body">
                        	<div id="accordion">
                        	<h5> Botones de pago</h5>';
		if ($pasarela["estado"]) {
			// echo serialize(array("nombre"=>"payulatam","IdAcount"=>87287237283,"ApiKey"=>"Kolip8932uj2"));
			$val = 0;
			for ($i=0; $i <count($pasarela["datos"]) ; $i++) { 
				// echo $pasarela["datos"][$i]["id"].' - ';
				if ($pasarela["datos"][$i]["id"]!=102) {					
					$table = $col = $fila = '';
					$datosPasarela = unserialize($pasarela["datos"][$i]["valor"]);
					foreach ($datosPasarela as $key => $valu) {
						$col .= '<th>'.$key.'</th>';
						if (!is_array($valu)) {
							$fila .= '<td>'.$valu.'</td>';
						}else{
							foreach ($valu as $key2 => $valu2) {
								# code...
							}
						}
					}
					$table .='
								<table>
							  		Datos '.$pasarela["datos"][$i]["nombre"].'
								  	<thead>
								  		<tr>
								  			'.$col.'
								  		</tr>
								  	</thead>
								  	<tbody>
								  		<tr>
								  			'.$fila.'
								  		</tr>
								  	</tbody>
							  	</table>
					';
					$val++;
					$param = 'check'.self::encriptTable($pasarela["datos"][$i]["id"]);
					$contenido .= '
						<div class="card">
						    <div class="card-header" id="heading'.$val.'">
						      <h5 class="mb-0">
						        <button class="btn btn-light" data-toggle="collapse" data-target="#collapsePasarela'.$val.'"  aria-controls="collapsePasarela'.$val.'">
						         	'.$pasarela["datos"][$i]["nombre"].'

						        </button>
					         	<label class="switch float-right">
								  <input type="checkbox" '.(($pasarela["datos"][$i]["estado"])?'checked':'').' name="'.$param.'" onchange="javascript:procesoMediosPagos.actDesBtn(\''.$param.'\', this)">
								  <span class="slider round"></span>
								</label>
						      </h5>
						    </div>

						    <div id="collapsePasarela'.$val.'" class="collapse" aria-labelledby="heading'.$val.'" data-parent="#accordion">
						      <div class="card-body">
						        '.$table.'
						      </div>
						    </div>
						</div>					
		    		';				
				}
			}
		}

		$contenido .= '	
						</div>
				    </div>
				</div>
			</div>
        ';
		
		return $contenido;
	}

	public static function cargar_config_general(){
		return '
			<div class="card-header">Configuración del sistema</div>	
			<div class="card-body">Config General del sistema</div>	
		';
	}

	public static function facturacion_titulo_empresa(){
		return '
		<div class="card-header">Datos de la empresa y facturación</div>	
		<div class="card-body">Facturación y titulo de la empresa</div>
		';
	}
	public static function traer_nombre_rol_dt($idRol = null){
		$nombre = " ---- ";
		if(!empty($idRol)){
			$datos = self::consultar_rol($idRol);
			if($datos["result"]){
				$nombre = $datos["datos"]["nombre"];
			}
		}
		return $nombre;
	}
	private static function consultar_rol($idRol){
		$conexion = self::iniciar();
		$sql = "SELECT id, nombre, estado, fecha FROM roles WHERE id = $idRol ";
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Error al cargar el rol";
		$datos["datos"] = array();
		if ($consu->num_rows>0) {
			$data = $consu->fetch_array();
			$datos["datos"] =  $data;			
			$datos["result"] = true;
			$datos["mensaje"] = "Rol cargado";
		}
		
		$conexion->close();
		return $datos;
	}

	private static function consultar_roles(){
		$conexion = self::iniciar();
		$sql = "SELECT id, nombre, estado, fecha FROM roles ";
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Error al cargar los roles";
		$datos["datos"] = array();
		if ($consu->num_rows>0) {
			while($data = $consu->fetch_array()){
				array_push($datos["datos"], $data);
			}
			$datos["result"] = true;
			$datos["mensaje"] = "Roles cargados";
		}
		
		$conexion->close();
		return $datos;
	}

	public static function editar_estado_rol($id, $valor){
		$sql = "UPDATE roles SET estado='$valor' WHERE id=$id ";
		$conexion = self::iniciar();
		$result = false;
		$mensaje = "No se realizó la actualización del rol ";
		if ($conexion->query($sql)) {
			$result = true;
			$mensaje = "Estado actualizado ";
		}
		$conexion->close();
		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	private static function cargar_permisos($idAdmin){
		$conexion = self::iniciar();
		$sql = "SELECT id, editar, crear, ver, eliminar FROM roles_permisos WHERE id_admin =  $idAdmin ";
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Error al cargar los permisos";
		$datos["datos"] = array();
		if ($consu->num_rows>0) {
			while($data = $consu->fetch_array()){
				array_push($datos["datos"], $data);
			}
			$datos["result"] = true;
			$datos["mensaje"] = "Permisos cargados";
		}
		
		$conexion->close();
		return $datos;
	}

	public static function editar_estado_permiso($id, $valor, $campo){
		$sql = "UPDATE roles_permisos SET $campo = '$valor ' WHERE id=$id";
		$conexion = self::iniciar();
		$result = false;
		$mensaje = "Los usuarios estandar del sistema no pueden ser editados ";
		if (($id!=1)) {
			$mensaje = "No fue posible editar el permiso ";
			if ($conexion->query($sql)) {
				$result = true;
				$mensaje = "Permiso $campo actualizado ";
			}
		}
		
		$conexion->close();
		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	private static function consultar_datos_usuario($idAdmin){
		$conexion = self::iniciar();
		$sql = "SELECT roles_id, usuario, fecha, telefono, correo, nombre FROM admin WHERE id = $idAdmin ";
		$consu = $conexion->query($sql);
		$datos["id"] = 0;	
		$datos["usuario"] = "Indefinido";
		$datos["mensaje"] = "Datos no encontrados";
		$datos["result"] = false;
		if($consu->num_rows>0){
			$rConsu = $consu->fetch_assoc();
			$datos["id"] = $rConsu["roles_id"];
			$datos["usuario"] = $rConsu["usuario"];
			$datos["nombre"] = $rConsu["nombre"];
			$datos["correo"] = $rConsu["correo"];
			$datos["telefono"] = $rConsu["telefono"];
			$datos["fecha"] = $rConsu["fecha"];
			$datos["mensaje"] = "Datos cargados";
			$datos["result"] = true;
		}
		$conexion->close();
		return $datos;
	}

	public static function cargar_datos_usuario_admin($idAdmin){
		$datosUsuarioActual = self::consultar_datos_usuario($idAdmin);
		$form = '
			<div class="modal-body">
				'.$datosUsuarioActual["mensaje"].'
			</div>
			<div class="modal-footer">
				<a class="btn btn-secondary " type="button" data-dismiss="modal" aria-label="Close">Cerrar</a>
			</div>
		';
		if ($datosUsuarioActual["result"]) {
			$form = '
				<form class="form-horizontal" name="formAsignarRol"id="formAsignarRol" method="post">
					<div class="modal-body">
						<div class="form-group">
							<input type="text" name="nombre" id="nombre" placeholder="Nombre" value="'.$datosUsuarioActual["nombre"].'">
						</div>					
					</div>
					<div class="modal-footer">
						<input type="hidden" value="asignarRolUsuario" name="entrada">
						<button class="btn btn-primary" type="submit" name="btnAsignarRol"><i class="fa fa-users"></i>Asingar rol</button>
						<a class="btn btn-secondary " type="button" data-dismiss="modal" aria-label="Close">Cerrar</a>
					</div>
				</form>
			';
		}
		$html = '
			<div class="modal-header">
				<h5 class="modal-title" id="nuevoUsuarioMdl">Editar datos del usuario <b id="tblCtUser">'.$datosUsuarioActual["usuario"].'</b></h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			'.$form.'
		';
		return array("result"=>$datosUsuarioActual["result"], "mensaje"=>$datosUsuarioActual["mensaje"], "html"=>$html);
	}

	public static function cargar_roles_asignacion($idAdmin = 0){
		$roles = self::consultar_roles();
		$datosRolActual = self::consultar_datos_usuario($idAdmin);
		$option = '';
		if ($roles["result"]) {
			foreach ($roles as $value) {
				if (is_array($value)) {
					for ($i=0; $i <count($value) ; $i++) {
						if ($value[$i]["estado"]) {
							$idRol =  self::encriptTable($value[$i]["id"]);						
							$nombre =  $value[$i]["nombre"];
							$option .= '<option value="'.$idRol.'" '.(($value[$i]["id"]==$datosRolActual["id"])?'selected':'').'>'.$nombre.'</option>';
						}
					}
				}
			}
		}
		$html = '
			<div class="modal-header">
				<h5 class="modal-title" id="nuevoUsuarioMdl">Asignar rol al usuario <b id="tblCtUser">'.$datosRolActual["usuario"].'</b></h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<form class="form-horizontal" name="formAsignarRol"id="formAsignarRol" method="post">
				<div class="modal-body">
					<div class="form-group">
						<select name="selectRol" class="form-control">
							<option value="">Seleccione un rol</option>
							'.$option.'
						</select>
					</div>					
				</div>
				<div class="modal-footer">
					<input type="hidden" value="asignarRolUsuario" name="entrada">
					<button class="btn btn-primary" type="submit" name="btnAsignarRol"><i class="fa fa-users"></i>Asingar rol</button>
					<a class="btn btn-secondary " type="button" data-dismiss="modal" aria-label="Close">Cerrar</a>
				</div>
			</form>
		';
		return array("result"=>$roles["result"], "mensaje"=>$roles["mensaje"], "html"=>$html);
	}

	public static function actualizar_rol_usuario($idAdmin=0, $idRol=0){
		try {
			$sql = "UPDATE admin SET roles_id = $idRol WHERE id = $idAdmin ";
			$conexion = self::iniciar();
			$conexion->query($sql);
			$conexion->close();

			return array("result"=>true, "mensaje"=>"Rol actualizado");
		} catch (Exception $th) {
			//throw $th;
			return array("result"=>false, "mensaje"=>"Error al actualizar el rol ".$th->getMessage());
		}
	}

	public static function cargar_roles_y_administracion(){
		$roles = self::consultar_roles();
		$html = '';
		if ($roles["result"]) {
			foreach ($roles as $value) {
				if (is_array($value)) {
					for ($i=0; $i <count($value) ; $i++) {						
						$idRol =  self::encriptTable($value[$i]["id"]);						
						$nombre =  $value[$i]["nombre"];
						$estado =  (($value[$i]["estado"])?'checked':'');
						$fecha =  $value[$i]["fecha"];
						$permisos = self::cargar_permisos($value[$i]["id"]);
						if($permisos["result"]){
							foreach ($permisos as $valuePermiso) {
								if (is_array($valuePermiso)) {
									for ($t=0; $t <count($valuePermiso) ; $t++) { 
										$disabled = (($valuePermiso[$t]["id"]==1)) ? 'disabled="disabled"' : '' ;
										$disabled2 = (($valuePermiso[$t]["id"]==1)) ? 'style="background-color:#6e707e"' : '' ;
										$idPermiso =  self::encriptTable($valuePermiso[$t]["id"]);
										$crear =  (($valuePermiso[$t]["crear"])?'checked '.$disabled:'');
										$editar =  (($valuePermiso[$t]["editar"])?'checked '.$disabled:'');
										$ver =  (($valuePermiso[$t]["ver"])?'checked '.$disabled:'');
										$eliminar =  (($valuePermiso[$t]["eliminar"])?'checked '.$disabled:'');

										$htmlPermisos = '
											<div class="row">
												<div class="col-sm-3"> 
													<label class="form-label">  Ver 
														<label class="switch ">
															<input type="checkbox" '.$ver.' name="'.$idPermiso.'" data-control="ver" onchange="javascript:configGeneral.modificarPermisoVer(\''.$idPermiso.'\', this)">
															<span class="slider round" '.$disabled2.'></span>
														</label>
													</label>
												</div>
												<div class="col-sm-3"> 
													<label class="form-label">  Crear 
														<label class="switch ">
															<input type="checkbox" '.$crear.' name="'.$idPermiso.'" data-control="crear" onchange="javascript:configGeneral.modificarPermisoCrear(\''.$idPermiso.'\', this)">
															<span class="slider round" '.$disabled2.'></span>
														</label>
													</label>
												</div>
												<div class="col-sm-3"> 
													<label class="form-label">  Editar 
														<label class="switch ">
															<input type="checkbox" '.$editar.' name="'.$idPermiso.'" data-control="editar" onchange="javascript:configGeneral.modificarPermisoEditar(\''.$idPermiso.'\', this)">
															<span class="slider round" '.$disabled2.'></span>
														</label>
													</label>
												</div>
												<div class="col-sm-3"> 
													<label class="form-label">  Eliminar 
														<label class="switch ">
															<input type="checkbox" '.$eliminar.' name="'.$idPermiso.'" data-control="eliminar" onchange="javascript:configGeneral.modificarPermisoEliminar(\''.$idPermiso.'\', this)">
															<span class="slider round" '.$disabled2.'></span>
														</label>
													</label>
												</div>
											</div>
										';
									}
								}								
							}
							
						}else{
							$htmlPermisos = $permisos["mensaje"];
						}
						$html .= '
							<div class="card shadow mb-4">
								<!-- Card Header - Accordion -->
								<a href="#collapseCard-'.$idRol.'" class="d-block card-header py-3 '/*.(($i!=0)?'collapsed':'')*/.'" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCard-'.$idRol.'">
									<h6 class="m-0 font-weight-bold text-primary"> Rol '.ucfirst($nombre).' (Activo desde '.$fecha.')</h6>
								</a>
								<!-- Card Content - Collapse -->
								<div class="collapse show'/*.(($i!=0)?'':'show')*/.'" id="collapseCard-'.$idRol.'" >
									<div class="card-body">
										<div class="form-group">
											
											<label class="switch ">
												<input type="checkbox" '.$estado.' name="'.$idRol.'" onchange="javascript:configGeneral.modificarEstadoRol(\''.$idRol.'\', this)">
												<span class="slider round"></span>
											</label>
											<label class="form-label">  Activar/Desactivar Rol </label>
										</div>
										
										<div class="card shadow mb-4">
											<div class="card-header">Permisos</div>
											<div class="card-body"> '.$htmlPermisos.' </div>
										</div>
									</div>
								</div>
							</div>
						';
					}
				}
				
			}
		}else{
			$html = $roles["mensaje"];
		}
		return '
			<div class="card-header">
				Gestión de roles y asignación de permisos 
				<a class="btn btn-success fa-pull-right" href="#" data-toggle="modal" data-target="#rolModal">
					<i class="fas fa-users fa-sm fa-fw mr-2"></i>
					Nuevo rol
				</a>
			</div>	
            <div class="card-body">'.$html.'</div>
		';
	}

	private static function insetar_datos_rol($nombre = ''){
		try {
			$conexion = self::iniciar();
			$sql = "INSERT INTO roles (nombre, estado, fecha) VALUES (?,?,?)";
			$sentencia = $conexion->prepare($sql);
			$sentencia->bind_param('sss', $v1, $v2, $v3);
			$v1 = $nombre;
			$v2 = '1';
			$v3 = self::fecha_sistema();
			
			$sentencia->execute();
			$result = true;
			$mensaje = " Rol creado ";

			$consu = $conexion->query("SELECT max(id) as nuevo_rol FROM roles");
			$rConsu = $consu->fetch_assoc();
			$id = $rConsu["nuevo_rol"];
			
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = "Error al insertar el nuevo rol ".$e->getMessage();
			$id = 0;
		}
		return array("result"=>$result, "mensaje"=>$mensaje, "id"=>$id);
	}

	private static function insetar_datos_rol_permisos($idRol=0, $ver=0, $crear=0, $editar=0, $eliminar=0){
		try {
			$conexion = self::iniciar();
			$sql = "INSERT INTO roles_permisos (id_admin, ver, crear, editar, eliminar) VALUES (?,?,?,?,?)";
			$sentencia = $conexion->prepare($sql);
			$sentencia->bind_param('issss', $v1, $v2, $v3, $v4, $v5);
			$v1 = (int)$idRol;
			$v2 = $ver;
			$v3 = $crear;
			$v4 = $editar;
			$v5 = $eliminar;

			$result = true;
			$mensaje = " Rol creado ";
			$sentencia->execute();
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = "Error al insertar el nuevo rol ".$e->getMessage();
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	public static function registrar_rol($nombre='', $ver=0, $crear=0, $editar=0, $eliminar=0){
		$datos = self::insetar_datos_rol($nombre);
		$result = $datos["result"];
		$mensaje = $datos["mensaje"];
		if($result){
			$datos = self::insetar_datos_rol_permisos($datos["id"], $ver, $crear, $editar, $eliminar);
			$result = $datos["result"];
			$mensaje = $mensaje.' y '.$datos["mensaje"];
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	private static function insetar_datos_usuario($usuario, $nombre, $correo, $telefono){
		try {
			$conexion = self::iniciar();
			$sql = "INSERT INTO admin (usuario, nombre, telefono, correo, estado, fecha, roles_id) VALUES (?,?,?,?,?,?,?)";
			$sentencia = $conexion->prepare($sql);
			$sentencia->bind_param('ssssssi', $v1, $v2, $v3, $v4, $v5, $v6, $v7);
			$v1 = $usuario;
			$v2 = $nombre;
			$v3 = $telefono;
			$v4 = $correo;
			$v5 = '1';
			$v6 = self::fecha_sistema();
			$v7 = 0;
			
			$sentencia->execute();
			$result = true;
			$mensaje = " Usuario creado ";
			
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = "Error al crear nuevo usuario ".$e->getMessage();
		}
		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	public static function registrar_nuevo_usuario($nombre='', $usuario='', $correo='', $telefono=''){
		$datos = self::insetar_datos_usuario($usuario, $nombre, $correo, $telefono);
		$result = $datos["result"];
		$mensaje = $datos["mensaje"];		

		return array("result"=>$result, "mensaje"=>$mensaje);
	}
}
?>