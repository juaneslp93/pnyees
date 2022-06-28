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

			$sql = "SELECT count(id) as ordenes FROM ordenes_compras WHERE estado_proceso='0' ";
			$consu = $conexion->query($sql);
			$rConsu = $consu->fetch_assoc();
			$ordenes = (($rConsu["ordenes"]>0)?$rConsu["ordenes"]:0);
			
			#contenido compras
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

			# contenido ordenes
			$contenido .= '<h6 class="dropdown-header">
                            Ordenes de compras sin procesar 
                        </h6>';
			$sql = "SELECT id, numero_orden, fecha FROM ordenes_compras WHERE estado_proceso='0'";
            $consu = $conexion->query($sql);
            while ($rConsu = $consu->fetch_assoc()) {
				$idEncrip = Conexion::encriptTable($rConsu["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
            	$nroOrden = $rConsu["numero_orden"];
            	$fechaOrden = $rConsu["fecha"];
            	$contenido .= '<a class="dropdown-item d-flex align-items-center" href="orden-compra-detalle-'.$idEncrip.'">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-shopping-cart text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">'.$fechaOrden.'</div>
                                        <span class="font-weight-bold">'.$nroOrden.'</span>
                                    </div>
                                </a>';
            }
			$existe = false;
			if($compras>0 && $ordenes==0){
				$existe = true;
				$contenido .= '<a class="dropdown-item text-center small text-gray-500" href="lista-compras">Ver todos</a>';
			}else if($ordenes>0 && $compras==0){
				$existe = true;
				$contenido .= '<a class="dropdown-item text-center small text-gray-500" href="lista-orden-compras">Ver todos</a>';
			}
            
			$html = '';
			if($existe){
				$html .= $contenido;
			}
			
			$conexion->close();
			$totalBell = $compras+$ordenes;
			$result = array('bell' => $totalBell, "notif_content"=>$html);

			return $result;
		}else{
			$result = array('bell' => 0, "notif_content"=>'');
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
		    $fecha = date("Y/m/d H:m:s", strtotime($cadena));
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

	public static function registrar_banco($nombre='', $cuenta='', $tipo='', $imagen=''){
		try {
			$conexion = self::iniciar();
			$sql = "INSERT INTO bancos (nombre, cuenta, tipo, estado, fecha, qr_img) VALUES (?,?,?,?,?,?)";
			$sentencia = $conexion->prepare($sql);
			$nombre = $nombre;
			$cuenta = $cuenta;
			$tipo = $tipo;
			$estado = '1';
			$fecha_registro = date('Y-m-d H:m:s');
			$imagen = $imagen;
			$sentencia->bind_param('ssssss', $nombre, $cuenta, $tipo, $estado, $fecha_registro, $imagen);
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
				$idEcnript = self::encriptTable($rConsu["id"]);
				$row .= '	<tr>
								<td>'.$rConsu["nombre"].'</td>
								<td>'.$rConsu["cuenta"].'</td>
								<td>'.(($rConsu["tipo"]=='1')?'Ahorros':'Corriente').'</td>
								<td>'.(($rConsu["estado"]=='1')?'
									<i class="btn btn-success btn-circle btn-lg">
										<i class="fa fa-check"></i>
									</i>':'
									<i class="btn btn-danger btn-circle btn-lg">
										<i class="fa fa-close"></i>
									</i>').'
								</td>
								<td>
									<div class="dropdown">
									  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton'.$Drop.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									   Acciones
									  </button>
									  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$Drop.'">
									   <a class="dropdown-item" href="javascript:procesoMediosPagos.editarBanco(\''.$idEcnript.'\')"><i class="fa fa-edit"></i> Editar</a>
									   <a class="dropdown-item" href="javascript:procesoMediosPagos.eliminarBanco(\''.$idEcnript.'\')""><i class="fa fa-trash"></i> Eliminar</a>
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
													<th>Opciones</th>											
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
		$datos = self::consultaSystem("relacion", "config_general");
		$form = '';
		if ($datos["estado"]) {
			for ($i=0; $i <count($datos["datos"]) ; $i++) {
				$nombre = $datos["datos"][$i]["nombre"];
				$nombreCampo = str_replace(' ', '_', $datos["datos"][$i]["nombre"]);
				$valor = $datos["datos"][$i]["valor"];
				$form .= '
					<div class="form-group">
						<label for="'.$nombreCampo.'">'.ucfirst($nombre).':</label>
						<input class="form-control" type="text" id="'.$nombreCampo.'" name="'.$nombreCampo.'" value="'.$valor.'" required>
					</div>
				';
			}
			$form .= '
				<div class="form-group">
					<input type="hidden" name="entrada" value="editarConfigGeneral">
					<button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> Actualizar datos</button>
				</div>
			';
		}
		return '
			<div class="card-header">Configuración del sistema</div>	
			<div class="card-body">
				<form class="form-validate was-validated" name="formEditarConfigGeneral" id="formEditarConfigGeneral">
				'.$form.'
				</form>
			</div>	
		';
	}

	public static function facturacion_titulo_empresa(){
		$pasarela = self::consultaSystem("relacion", "config_facturacion");
		$form = '';
		if ($pasarela["estado"]) {
			for ($i=0; $i <count($pasarela["datos"]) ; $i++) {
				$nombre = $pasarela["datos"][$i]["nombre"];
				$valor = $pasarela["datos"][$i]["valor"];
				$form .= '
					<div class="form-group">
						<input class="form-control" type="text" name="'.$nombre.'" value="'.$valor.'" required>
					</div>
				';
			}
			$form .= '
				<div class="form-group">
					<input type="hidden" name="entrada" value="editarFactData">
					<button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> Actualizar datos</button>
				</div>
			';
		}
		return '
		<div class="card-header">Datos de la empresa y facturación</div>	
		<div class="card-body">
			<form class="form-validate was-validated" name="formEditarFactData" id="formEditarFactData">
			'.$form.'
			</form>
		</div>
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
		$sql = "SELECT id, editar, crear, ver, eliminar, id_modulo FROM roles_permisos WHERE id_admin =  $idAdmin ";
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

	private static function datos_modulos(){
		$conexion = self::iniciar();
		$sql = "SELECT id, modulo, estado FROM modulos_activos ";
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Error al cargar los modulos";
		$datos["datos"] = array();
		if ($consu->num_rows>0) {
			while($data = $consu->fetch_array()){
				array_push($datos["datos"], $data);
			}
			$datos["result"] = true;
			$datos["mensaje"] = "Modulos cargados";
		}
		
		$conexion->close();
		return $datos;
	}

	public static function datos_roles(){
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
		$moderadores    = Self::saber_permiso_asociado(2);
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
				<form class="form-horizontal was-validated" name="formEditarUsuario"id="formEditarUsuario" method="post">
					<div class="modal-body">
						<div class="form-group">
							<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="'.$datosUsuarioActual["nombre"].'" '.((!$moderadores["editar"])?'disabled':'').'>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="correo" id="correo" placeholder="Correo" value="'.$datosUsuarioActual["correo"].'" '.((!$moderadores["editar"])?'disabled':'').'>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="'.$datosUsuarioActual["telefono"].'" '.((!$moderadores["editar"])?'disabled':'').'>
						</div>					
					</div>
					'.(($moderadores["editar"])?'
					<div class="modal-footer">
						<input type="hidden" value="editarDatosUsuario" name="entrada">
						<button class="btn btn-primary" type="submit" name="btnAsignarRol"><i class="fa fa-refresh"></i> Actualizar datos</button>
						<a class="btn btn-secondary " type="button" data-dismiss="modal" aria-label="Close">Cerrar</a>
					</div>
					':'').'					
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

	private static function consultar_roles_y_permisos_modulos(){
		$conexion = self::iniciar();
		$sql = "SELECT 
			rp.id, 
			r.nombre, 
			r.estado AS estado_rol, 
			r.fecha, 
			m.modulo, 
			m.estado AS estado_modulo, 
			rp.ver, 
			rp.crear, 
			rp.editar, 
			rp.eliminar, 
			rp.id_admin, 
			rp.id_modulo 
		FROM roles_permisos AS rp 
		LEFT JOIN roles AS r ON r.id = rp.id_admin 
		LEFT JOIN modulos_activos AS m ON m.id = rp.id_modulo ORDER BY rp.id ASC";
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

	public static function cargar_roles_y_administracion($moderadoresPermisos){
		$roles = self::consultar_roles_y_permisos_modulos();
		$html = $estructura = $tr = '';
		if ($roles["result"]) {
			$nombre = '';
			$modulo = '';
			$struc = array();
			$struc2 = array();
			$struc3["nombre"] = array();
			$struc3["rols"] = array();
			for ($i=0; $i <count($roles["datos"]) ; $i++) {
				foreach ($roles["datos"][$i] as $key => $value) {
					if(!is_numeric($key)){
						$base = ucfirst($roles["datos"][$i]["modulo"]);
						switch ($key) {
							case 'nombre':
								$nombre = $value;								
								if(!in_array($nombre, $struc)){
									array_push($struc, $nombre);
								}
							break;
							case 'modulo':
								$modulo = $value;
								if(!in_array($modulo, $struc2)){
									array_push($struc2, $modulo);
								}
							break;
							default:
								# No detecta
								break;
						}
					}
				}				
			}
			for ($i=0; $i < count($roles["datos"]) ; $i++) { 
				if(in_array($roles["datos"][$i]["nombre"], $struc)){
					$nombre 		= $roles["datos"][$i]["nombre"];
					$modulo 		= $roles["datos"][$i]["modulo"];
					$estadoModulo 	= (($roles["datos"][$i]["estado_modulo"])?'checked':'');
					$ver 			= (($roles["datos"][$i]["ver"]=="1")?'checked':'');
					$crear 			= (($roles["datos"][$i]["crear"]=="1")?'checked':'');
					$editar 		= (($roles["datos"][$i]["editar"]=="1")?'checked':'');
					$eliminar 		= (($roles["datos"][$i]["eliminar"]=="1")?'checked':'');
					$idAdmin 		= self::encriptar($roles["datos"][$i]["id_admin"], 'Rip');
					$idModulo 		= self::encriptar($roles["datos"][$i]["id_modulo"], 'Rip');
					$idRol 			= self::encriptar($roles["datos"][$i]["id"], 'Rip');
					$estructura = '
						<tr>
							<th colspan="3">'.$modulo.'</th>
							<td colspan="1">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="'.$i.$modulo.'" name="'.$i.$modulo.'" value="1" data-type="mod" '.$estadoModulo.' '.(($moderadoresPermisos["editar"])?'':'disabled').'>
									<label class="custom-control-label" for="'.$i.$modulo.'"> </label>
								</div>
								<input type="hidden" name="data-control-'.$i.$modulo.'" value="'.$idAdmin.'">
								<input type="hidden" name="data-type-'.$i.$modulo.'" value="'.$idModulo.'">
								<input type="hidden" name="data-ref-'.$i.$modulo.'" value="'.$idRol.'">
								<input type="hidden" name="nombre'.$i.'" value="'.$modulo.'">
							</td>
						</tr>
						<tr>
							<th>Ver</th>
							<th>Crear</th>
							<th>Editar</th>
							<th>Eliminar</th>
						</tr>
						<tr>
							<td>
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="ver'.$i.$modulo.'" name="ver'.$i.$modulo.'" data-type="sub" value="1" '.$ver.' '.(($moderadoresPermisos["editar"])?'':'disabled').'>
									<label class="custom-control-label" for="ver'.$i.$modulo.'"> </label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="editar'.$i.$modulo.'" name="editar'.$i.$modulo.'" data-type="sub" value="1" '.$editar.' '.(($moderadoresPermisos["editar"])?'':'disabled').'>
									<label class="custom-control-label" for="editar'.$i.$modulo.'"> </label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="crear'.$i.$modulo.'" name="crear'.$i.$modulo.'" data-type="sub" value="1" '.$crear.' '.(($moderadoresPermisos["editar"])?'':'disabled').'>
									<label class="custom-control-label" for="crear'.$i.$modulo.'"> </label>
								</div>
							</td>
							<td>
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="eliminar'.$i.$modulo.'" name="eliminar'.$i.$modulo.'" data-type="sub" value="1" '.$eliminar.' '.(($moderadoresPermisos["editar"])?'':'disabled').'>
									<label class="custom-control-label" for="eliminar'.$i.$modulo.'"> </label>
								</div>
							</td> 
						</tr>	
					';

					if(!in_array($roles["datos"][$i]["nombre"], $struc3["nombre"])){
						array_push($struc3["nombre"], $roles["datos"][$i]["nombre"]);
					}
					array_push($struc3["rols"], array("nombreRol"=>$roles["datos"][$i]["nombre"], "esctr"=>$estructura));
				}
			}

			for ($i=0; $i <count($struc3["nombre"]) ; $i++) {
				$tr = '';
				for ($r=0; $r <count($struc3["rols"]) ; $r++) { 
					if($struc3["nombre"][$i]==$struc3["rols"][$r]["nombreRol"]){
						$tr.= $struc3["rols"][$r]["esctr"];
					}
				}
				$html .= '	
					<div class="form-group">
						<label for="'.$i.'nombreRol">Nombre del rol:</label>
						<input type="text" name="nombreRol'.$i.'" id="'.$i.'nombreRol" placeholder="Nombre del rol" class="form-control" value="'.$struc3["nombre"][$i].'" '.(($moderadoresPermisos["editar"])?'':'disabled').'>
					</div>				
					<div class="row">
						<div class="col-lg-12">
							<table class="table table-striped table-bordered">
								<thead>
									<th>Permisos</th>
								</thead>
								<tbody>
									'.$tr.'
								</tbody>
							</table> 
						</div>
					</div>
				';
			}
		}else{
			$html .= $roles["mensaje"];
		}
		return (($moderadoresPermisos["crear"])?'
			<div class="card-header">
				Gestión de roles y asignación de permisos 
				<a class="btn btn-success fa-pull-right" href="#" data-toggle="modal" data-target="#rolModal">
					<i class="fas fa-users fa-sm fa-fw mr-2"></i>
					Nuevo rol
				</a>
			</div>	
			':'').'			
            <div class="card-body">
			<div class="row">
				<div class="col-lg-12">
					<form class="form-horizontal" name="formEditarRol" id="formEditarRol" method="post">
						'.$html.'
						'.(($moderadoresPermisos["editar"])?'
						<input type="hidden" name="entrada"	value="actualizarRolesPermisos">
						<button type="submit" class="btn btn-success btn-md"> <i class="fa fa-refresh"></i> Actualizar permisos</button>
						':'').'
					</form>
				</div>
			</div>
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

	private static function insetar_datos_rol_permisos($idRol=0, $ver=0, $crear=0, $editar=0, $eliminar=0, $id_modulo=0, $nombre=''){
		try {
			$conexion = self::iniciar();
			$sql = "INSERT INTO roles_permisos (id_admin, ver, crear, editar, eliminar, id_modulo) VALUES (?,?,?,?,?,?)";
			$sentencia = $conexion->prepare($sql);
			$v1 = (int)$idRol;
			$v2 = $ver;
			$v3 = $crear;
			$v4 = $editar;
			$v5 = $eliminar;
			$v6 = (int)$id_modulo;
			$sentencia->bind_param('issssi', $v1, $v2, $v3, $v4, $v5, $v6);
			$result = true;
			$mensaje = " Permiso para $nombre agregado ";
			$sentencia->execute();
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = "Error al insertar el nuevo rol ".$e->getMessage();
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	private static function saber_nombre_modulo_activo($idModulo){
		$conexion = self::iniciar();
		$sql = "SELECT modulo FROM  modulos_activos WHERE id =  $idModulo AND estado='1'";
		$consu = $conexion->query($sql);
		$result = array("existe"=>false, "nombre"=>'');
		if($consu->num_rows>0){
			$rConsu = $consu->fetch_assoc();
			if($rConsu["modulo"]!=null){
				$result = array("existe"=>true, "nombre"=>$rConsu["modulo"]);
			}
		}
		$conexion->close();
		return $result;
	}

	private static function saber_id_modulo_activo($nombreModulo){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM  modulos_activos WHERE modulo LIKE '$nombreModulo' AND estado='1'";
		$consu = $conexion->query($sql);
		$result = array("existe"=>false, "id"=>'');
		if($consu->num_rows>0){
			$rConsu = $consu->fetch_assoc();
			if($rConsu["id"]!=null){
				$result = array("existe"=>true, "id"=>$rConsu["id"]);
			}
		}
		$conexion->close();
		return $result;
	}

	public static function registrar_rol($nombre='', $modulos=array()){
		$datos = self::insetar_datos_rol($nombre);
		$result = $datos["result"];
		$mensaje = $datos["mensaje"];
		$idRol = $datos["id"];
		$mods = self::datos_modulos();
		$modulosActivos = array();
		$mensaje = '';
		if($mods["result"]){
			for ($i=0; $i <count($mods["datos"]) ; $i++) { 
				array_push($modulosActivos, array("modulo"=>$mods["datos"][$i]["modulo"], "idModulo"=>$mods["datos"][$i]["id"]));
			}
		}
		if($result){
			$ver = 0;
			$crear = 0;
			$editar = 0;
			$eliminar = 0;
			$permisoVer = 0;
			$permisoCrear = 0;
			$permisoEditar = 0;
			$permisoEliminar = 0;
			$mod = array();
			// var_dump($modulos);
			for ($t=0; $t <count($modulos) ; $t++) {#buscamos los modulos activos y validamos si están activos
				for ($y=0; $y <count($modulosActivos) ; $y++) {
					if($modulosActivos[$y]["modulo"]==$modulos[$t]["nombre"]){
						array_push(
							$mod, 
							array(
								"idModulo"=>$modulosActivos[$y]["idModulo"], 
								"nombreMod"=>$modulosActivos[$y]["modulo"]
							)
						);
					}
				}
				for ($l=0; $l <count($mod) ; $l++) { 
					if($mod[$l]["nombreMod"]==$modulos[$t]["nombre"]){
						$ver = "ver".ucfirst($mod[$l]["nombreMod"]);
						$crear = "crear".ucfirst($mod[$l]["nombreMod"]);
						$editar = "editar".ucfirst($mod[$l]["nombreMod"]);
						$eliminar = "eliminar".ucfirst($mod[$l]["nombreMod"]);
					}
					if(@is_numeric($modulos[$t][$ver])){
						@$permisoVer = $modulos[$t][$ver];
					}
					if(@is_numeric($modulos[$t][$crear])){
						@$permisoCrear = $modulos[$t][$crear];
					}
					if(@is_numeric($modulos[$t][$editar])){
						@$permisoEditar = $modulos[$t][$editar];
					}
					if(@is_numeric($modulos[$t][$eliminar])){
						@$permisoEliminar = $modulos[$t][$eliminar];
					}

					if($mod[$l]["nombreMod"]==$modulos[$t]["nombre"]){
						$datoIn = self::insetar_datos_rol_permisos($idRol, $permisoVer, $permisoCrear, $permisoEditar, $permisoEliminar, $mod[$l]["idModulo"], $mod[$l]["idModulo"]);
						$result = $datoIn["result"];
						$mensaje = $mensaje.','.$datoIn["mensaje"];
					}		
				}				
			}
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

	public static function proceso_actualizacion_roles($post){
		$roles["nombreRol"] = array();
		$roles["datos"] = array();
		$result = false;
		$mensaje = '';
		$mod = array();
		for ($i=0; $i <count($post) ; $i++) {#recorremos el post
			$idRol = 0;	
			if(!empty($post["nombreRol".$i])){
				$rol = $post["nombreRol".$i];# obtenemos el nombre del rol
				if(!in_array($rol, $roles["nombreRol"])){
					array_push($roles["nombreRol"], $rol);
				}
			}
			$mods = self::datos_modulos();
			$modulosActivos = array();			
			if($mods["result"]){				
				for ($l=0; $l <count($mods["datos"]) ; $l++) { 					
					if(!empty($post[$i.$mods["datos"][$l]["modulo"]])){
						if (array_key_exists($i.$mods["datos"][$l]["modulo"], $post)) {
							array_push($modulosActivos, array("modulo"=>$mods["datos"][$l]["modulo"], "idModulo"=>$mods["datos"][$l]["id"]));
						}
					}
				}			
				$ver = 0;
				$crear = 0;
				$editar = 0;
				$eliminar = 0;
				$permisoVer = 0;
				$permisoCrear = 0;
				$permisoEditar = 0;
				$permisoEliminar = 0;
				$mod = array();				
				for ($y=0; $y <count($modulosActivos) ; $y++) {
					if(in_array($modulosActivos[$y]["modulo"], $post)){
						array_push(
							$mod, 
							array(
								"idModulo"=>$modulosActivos[$y]["idModulo"], 
								"nombreMod"=>$modulosActivos[$y]["modulo"]
							)
						);
					}
				}
				for ($l=0; $l <count($mod) ; $l++) {
					if($mod[$l]["nombreMod"]==$post["nombre".$i]){
						$ver = "ver".$i.$mod[$l]["nombreMod"];
						$crear = "crear".$i.$mod[$l]["nombreMod"];
						$editar = "editar".$i.$mod[$l]["nombreMod"];
						$eliminar = "eliminar".$i.$mod[$l]["nombreMod"];
						$tipo = "data-type-".$i.$mod[$l]["nombreMod"];
						$ref = "data-ref-".$i.$mod[$l]["nombreMod"];
						$control = "data-control-".$i.$mod[$l]["nombreMod"];
						if(!empty($post[$control])){
							$idAdmin = self::desencriptar(@$post[$control],'Rip');
						}
						if(!empty($post[$ref])){
							$idRol = self::desencriptar(@$post[$ref],'Rip');
						}
						if(!empty($post[$tipo])){
							$idModulo = self::desencriptar(@$post[$tipo],'Rip');
						}
					}
					if(@is_numeric($post[$ver])){
						@$permisoVer = $post[$ver];
					}
					if(@is_numeric($post[$crear])){
						@$permisoCrear = $post[$crear];
					}
					if(@is_numeric($post[$editar])){
						@$permisoEditar = $post[$editar];
					}
					if(@is_numeric($post[$eliminar])){
						@$permisoEliminar = $post[$eliminar];
					}
					if($mod[$l]["nombreMod"]==$post["nombre".$i]){
						$datoIn = self::actualizar_datos_rol_permisos($idRol, $permisoVer, $permisoCrear, $permisoEditar, $permisoEliminar, $idAdmin, $idModulo);
						$result = $datoIn["result"];
						$mensaje = $datoIn["mensaje"];
					}		
				}
			}
		}
		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	private static function actualizar_datos_rol_permisos($idRol=0, $ver=0, $crear=0, $editar=0, $eliminar=0, $idAdmin=0, $idModulo=0){
		try {
			$v1 = $ver;
			$v2 = $crear;
			$v3 = $editar;
			$v4 = $eliminar;
			$v5 = (int)$idRol;
			$v6 = (int)$idAdmin;
			$v7 = (int)$idModulo;
			$conexion = self::iniciar();
			$sql = "UPDATE roles_permisos SET ver=?, crear=?, editar=?, eliminar=? WHERE id=? AND id_admin=? AND id_modulo=? ";
			$sentencia = $conexion->prepare($sql);			
			$sentencia->bind_param('sssssii', $v1, $v2, $v3, $v4, $v5, $v6, $v7);
			$result = true;
			$mensaje = " Permisos actualizados ";
			$sentencia->execute();
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = "Error al insertar el nuevo rol ".$e->getMessage();
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	public static function editar_datos_usuario($idUser, $nombre, $correo, $telefono){
		try {
			$sql = "UPDATE admin SET nombre = '$nombre', correo = '$correo', telefono = '$telefono' WHERE id = $idUser ";
			$conexion = self::iniciar();
			$conexion->query($sql);
			$conexion->close();

			return array("result"=>true, "mensaje"=>"Datos actualizados");
		} catch (Exception $th) {
			//throw $th;
			return array("result"=>false, "mensaje"=>"Error al actualizar los datos del usuario. ".$th->getMessage());
		}
	}

	public static function eliminar_usuario_admin($idUser=0){
		if($idUser>0){
			return self::eliminar_usuario_admin_($idUser);
		}else{
			return array("result"=>false, "mensaje"=>"No se reconoce el identificador. ");
		}
	}

	private static function eliminar_usuario_admin_($idUser){
		try {
			$sql = "UPDATE admin SET estado='0' WHERE id = $idUser ";
			$conexion = self::iniciar();
			$conexion->query($sql);
			$conexion->close();

			return array("result"=>true, "mensaje"=>"Usuario eliminado");
		} catch (Exception $th) {
			//throw $th;
			return array("result"=>false, "mensaje"=>"Error al actualizar los datos del usuario. ".$th->getMessage());
		}
	}
	public static function procesar_imagen_pasarela($FILES, $nombreFile, $eliminarOld = false, $imgOld=''){
		$folder = "assets/img/qr";
		if($eliminarOld){
			if(!empty($imgOld)){
				@unlink("../$folder/$imgOld");
			}			
		}		
		$FILES['upfile'] = $FILES[''.$nombreFile];
		unset($FILES[''.$nombreFile]);
		$po = self::validar_archivo($FILES, $folder);
		$data = explode("//~",$po);
		$continue = false;
		$url = '';
		$mensaje = $data[0];
		if ($data[1]) {
			$continue = true;
			$exp = explode("/",@$data[1]);
			$url = end($exp);
			$mensaje = @$data[0];
		}
		$result = array("existe"=>$continue, 'url' => $url, "mensaje"=>$mensaje);			
		return $result;
	}

	public static function cargar_datos_banco($idBanco){
		$html = '';
		$continue = false;
		$mensaje = 'Hubo un problema al tratar de cargar los datos';
		if($idBanco>0){
			$datos = self::cargar_datos_banco_($idBanco);
			$continue = $datos["existe"];
			if($datos["existe"]){
				$mensaje = 'Datos cargados';
				$nombre = $datos["datos"]["nombre"];
				$tipo = $datos["datos"]["tipo"];
				$cuenta = $datos["datos"]["cuenta"];
				$fecha = $datos["datos"]["fecha"];
				$estado = $datos["datos"]["estado"];
				$qr_img = $datos["datos"]["qr_img"];

				$html .= '
					<div class="row">
						<div class="col-lg-12">
							<form class="user was-validated" id="formEditarBanco" >
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<input type="text" class="form-control"
											id="nombre" name="nombre" placeholder="Nombre del banco" value="'.$nombre.'" required>
									</div>
									<div class="col-sm-6 mb-3 mb-sm-0">
										<select name="tipo" id="tipo" class="form-control " required>
											<option '.(($tipo=='')?'selected':'').' value="">Tipo de cuenta</option>
											<option '.(($tipo=='1')?'selected':'').' value="1">Ahorros</option>
											<option '.(($tipo=='2')?'selected':'').' value="2">Corriente</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="cuenta" value="'.$cuenta.'" id="cuenta"
											placeholder="Numero de cuenta" required>
								</div>
								<div class="form-group">
									<input type="hidden" name="img_qr" value="'.$qr_img.'">
									<input type="file" name="qrCharge" id="qrCharge" placeholder="Editar Qr" accept="image/*" class="form-control-file" >
								</div>
								<div class=" text-center align-self-center align-items-center">
									<img class="img-thumbnail" src="'.URL_ABSOLUTA.'assets/img/qr/'.$qr_img.'" alt="'.$qr_img.'" >		
								</div>
								<input type="hidden" name="entrada" value="editarBanco">
								<input type="hidden" name="banco" value="'.self::encriptTable($idBanco).'">
								<button type="submit" class="btn btn-warning btn-user btn-block">
									<i class="fa fa-refresh"></i> Actualizar cuenta
								</button>
								<hr>
							</form>
						</div>
					</div>
				';
			}else{
				$html .= '<i class="fa fa-warning text-warning"></i> Sin datos';
			}
		}else{
			$html .= '<i class="fa fa-close text-danger"></i> No se reconoce el banco';
		}
		return array("result"=>$continue, "html"=>$html, "mensaje"=>$mensaje);
	}

	private static function cargar_datos_banco_($idBanco){
		$conexion = self::iniciar();
		$sql = "SELECT nombre, tipo, cuenta, fecha, estado, qr_img FROM bancos WHERE id = $idBanco ";
		$consu = $conexion->query($sql);
		if($consu->num_rows>0){
			$datos["existe"] = true;
			$datos["datos"] = $consu->fetch_array();
		}else{
			$datos["existe"] = false;
		}
		$conexion->close();
		return $datos;
	}

	public static function actualizar_banco($nombre='', $cuenta='', $tipo='', $imagen='', $id){
		try {
			$conexion = self::iniciar();
			$sql = "UPDATE bancos SET nombre=?, cuenta=?, tipo=?, estado=?, qr_img=? WHERE id=?";
			$sentencia = $conexion->prepare($sql);
			$nombre = $nombre;
			$cuenta = $cuenta;
			$tipo = $tipo;
			$estado = '1';
			$imagen = $imagen;
			$sentencia->bind_param('sssssi', $nombre, $cuenta, $tipo, $estado, $imagen, $id);
			$result = true;
			$mensaje = 'Cuenta actualizada';
			$sentencia->execute();
					
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Hubo un problema al insertar los datos. ['.$e->getMessage().']</h1></span>';
		}
		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	public static function eliminar_banco($id=0){
		try {
			$conexion = self::iniciar();
			$sql = "UPDATE bancos SET estado=? WHERE id=?";
			$sentencia = $conexion->prepare($sql);
			$estado = '0';
			$sentencia->bind_param('si', $estado, $id);
			$result = true;
			$mensaje = 'Cuenta eliminada';
			$sentencia->execute();
					
			$conexion->close();
			
		} catch (Exception $e) {
			$result = false;
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Hubo un problema al insertar los datos. ['.$e->getMessage().']</h1></span>';
		}
		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	public static function datos_grafico_global()	{		
		$grafica = array();
		$conexion = self::iniciar();
		#consultamos la cantidad de usuarios registrados no eliminados
		$sql = "SELECT count(id) AS cant_usuarios FROM usuarios WHERE estado='1' ";
		$consu = $conexion->query($sql);
		$rConsu = $consu->fetch_assoc();
		$cantUser = $rConsu["cant_usuarios"];
		$grafica["usuarios"] = $cantUser;
		# consultamos la cantidad de compras aprobadas
		$sql = "SELECT count(id) AS cant_compras FROM compras WHERE estado_proceso='1' AND estado_aprobacion='1' ";
		$consu = $conexion->query($sql);
		$rConsu = $consu->fetch_assoc();
		$cantCompras = $rConsu["cant_compras"];
		$grafica["compras"] = $cantCompras;
		# consultamos las compras con envíos aprobados
		$sql = "SELECT count(id) AS cant_envio FROM compras WHERE estado_proceso='1' AND estado_aprobacion='1' AND estado_envio='1'";
		$consu = $conexion->query($sql);
		$rConsu = $consu->fetch_assoc();
		$cantEnvio = $rConsu["cant_envio"];
		$grafica["envios"] = $cantEnvio;
		$conexion->close();

		return $grafica;
	}

	public static function cargar_datos_diseno(){
		$datos = self::consultaSystem("relacion", "config_diseno");
		$form = '';
		$ul = $nav = $text = '';
		if ($datos["estado"]) {
			$form = '<form class="form-vertical" id="formEditDiseno">';
			for ($i=0; $i <count($datos["datos"]) ; $i++) {
				$ul = $nav = '';
				$id = (int)$datos["datos"][$i]["id"];
				$nombre = $datos["datos"][$i]["nombre"];
				$nombreCampo = ucfirst($datos["datos"][$i]["nombre"]);
				$valor = $datos["datos"][$i]["valor"];
				$togled = ' toggled';
				$opcionesMenu = array(
					'navbar-nav bg-gradient-default sidebar sidebar-light accordion',
					'navbar-nav bg-gradient-light sidebar sidebar-light accordion', 
					'navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion', 
					'navbar-nav bg-gradient-dark sidebar sidebar-dark accordion', 
					'navbar-nav bg-gradient-info sidebar sidebar-dark accordion',
					'navbar-nav bg-gradient-primary sidebar sidebar-dark accordion', 
					'navbar-nav bg-gradient-success sidebar sidebar-dark accordion', 
					'navbar-nav bg-gradient-warning sidebar sidebar-dark accordion',
					'navbar-nav bg-gradient-danger sidebar sidebar-dark accordion',
				);
				$opcionesTopbar = array(
					'navbar navbar-expand navbar-light bg-white text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-secondary text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-dark text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-info text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-success text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-primary text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-warning text-black-50 topbar mb-4 static-top shadow',
					'navbar navbar-expand navbar-dark bg-danger text-black-50 topbar mb-4 static-top shadow',

				);

				$opcionesTexto = array(
					'text-white',
					'text-white-500',
					'text-black',
					'text-black-50',
					'text-success',
					'text-info',
					'text-warning',
					'text-danger',
				);
				
				if($id==20){#menu
					$ul .= "<legend>$nombreCampo</legend>";
					for ($t=0; $t <count($opcionesMenu) ; $t++) { 
						$clase = $opcionesMenu[$t];
						$checked = '';
						if($clase===$valor) $checked = 'checked';
						$ul .= '
							<div class="col-lg-1 text-center align-self-center align-items-center card-body">
								<ul	class="'.$clase.$togled.'" id="">
									<!-- Divider -->
									<hr class="sidebar-divider my-0">

									
										<!-- Nav Item - Dashboard -->
										<li class="nav-item active">
											<a class="nav-link" href="#">
												<label for="'.$nombre.'">menu '.($t+1).'</label>
												<input type="radio" class="form-radio switch" id="'.$nombre.'" name="'.$nombre.'" value="'.$clase.'" '.$checked.'><hr class="sidebar-divider">
												<i class="fas fa-fw fa-tachometer-alt"></i>
												<span>Opción</span>
											</a>
										</li>
														

									<!-- Divider -->
									<hr class="sidebar-divider">

									<!-- Heading -->
									<div class="sidebar-heading">
										Elemento
									</div>

									
										<!-- Nav Item - Pages Collapse Menu -->
										<li class="nav-item">
											<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEx'.$t.'" aria-expanded="true" aria-controls="collapseEx'.$t.'">
												<i class="fas fa-fw fa-user"></i>
												<span>Opción</span>
											</a>
											<div id="collapseEx'.$t.'" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
												<div class="bg-white py-2 collapse-inner rounded">
													<h6 class="collapse-header">Elemento:</h6>
													<a class="collapse-item" href="#">Opción</a>
												</div>
											</div>
										</li>												

									<!-- Divider -->
									<hr class="sidebar-divider">

									<!-- Sidebar Toggler (Sidebar) -->
									<div class="text-center d-none d-md-inline">
										<button class="rounded-circle border-0" id="sidebarToggle"></button>
									</div>
								</ul>
							</div>
						';
					}
				}
				if($id==21){#topbar
					$nav .= "<legend>$nombreCampo</legend>";
					for ($t=0; $t <count($opcionesTopbar) ; $t++) { 
						$clase = $opcionesTopbar[$t];
						$checked = '';
						// echo "$clase===$valor <br>";
						if($clase===$valor) $checked = 'checked';
						$nav .= '
							<div class="col-lg-12 text-center align-self-center align-items-center card-body row">
								<div class="col-lg-1">									
									<input type="radio" class="form-radio switch" id="'.$nombre.'" name="'.$nombre.'" value="'.$clase.'" '.$checked.'><hr class="sidebar-divider">
								</div>
								<div class="col-lg-11">
									<nav class="'.$clase.'">
										<!-- Sidebar - Brand -->
										<a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
											<div class="sidebar-brand-text mx-3"><img src="'.URL_ABSOLUTA.'assets/img/icono.jfif" class="rounded mx-auto d-block img-fluid " width="80"> </div>
										</a>
										<!-- Sidebar Toggle (Topbar) -->
										<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
											<i class="fa fa-bars"></i>
										</button>
					
										<!-- Topbar Navbar -->
										<ul class="navbar-nav ml-auto">
					
											<!-- Nav Item - Alerts -->
											<li class="nav-item dropdown no-arrow mx-1">
												<a class="nav-link dropdown-toggle" href="#" id="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fas fa-bell fa-fw"></i>
													<!-- Counter - Alerts -->
													<span class="badge badge-danger badge-counter" id="notif-bel">0</span>
												</a>
												<!-- Dropdown - Alerts -->
												<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown" id="notif-content"></div>
											</li>
					
											<div class="topbar-divider d-none d-sm-block"></div>
					
											<!-- Nav Item - User Information -->
											<li class="nav-item dropdown no-arrow">
												<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<span class="mr-2 d-none d-lg-inline text-gray-50 small">Administrador</span>
													<img class="img-profile rounded-circle" src="'.URL_ABSOLUTA.'assets/img/undraw_profile.svg">
												</a>
												<!-- Dropdown - User Information -->
												<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
													<a class="dropdown-item" href="#">
														<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
														Perfil
													</a>
													<a class="dropdown-item" href="#">
														<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
														Configuración
													</a>
													<a class="dropdown-item" href="#">
														<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
														Registro de actividades
													</a>
													<div class="dropdown-divider"></div>
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#">
														<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
														Salir
													</a>
												</div>
											</li>					
										</ul>										
									</nav>
								</div>
							</div>
						';
					}
				}
				if($id==22){;
					$text .= "<legend>$nombreCampo tienda</legend>";
					$text .= '<div class="col-lg-12 text-center align-self-center align-items-center card-body row">';
					
					for ($t=0; $t <count($opcionesTexto) ; $t++) { 
						$clase = $opcionesTexto[$t];
						$checked = '';
						// echo "$clase===$valor <br>";
						if($clase===$valor) $checked = 'checked';
						$text .= '
							
								<div class="col-lg-3">		
									<label for="'.$nombre.'">'.$clase.'</label>							
									<input type="radio" class="form-radio switch" id="'.$nombre.'" name="'.$nombre.'" value="'.$clase.'" '.$checked.'>
								
									<p class="'.$clase.' '.(($clase==="text-white")?'bg-gray':'').'">
										Esto es un texto.									
									</p>
								</div>
						';
					}
					$text .= '</div>';
				}

				$form .= '
					<div class="col-lg-12"><div class="row">'.$ul.'</div></div>
					<div class="col-lg-12">'.$nav.'</div>
					<div class="col-lg-12"> '.$text.'</div>
				';
			}
			$form .= '
					<div class="form-group">
						<input type="hidden" name="entrada" value="editarDiseno">
						<button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> Actualizar datos</button>
					</div>
				</form>
			';
		}
		return array("result"=>$datos["estado"], "mensaje"=>$datos["mensaje"], "html"=>$form);
	}
}
?>