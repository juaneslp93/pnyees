<?php 
/**
 * 
 */
class OrdenesCompra extends Conexion
{
	
	function __construct()	{
		# code...
	}	

	public static function saber_nombre_usuario($idUsuario=0){
		$conexion = self::iniciar();
		$sql = "SELECT usuario FROM usuarios WHERE id=$idUsuario ";
		$consu = $conexion->query($sql);
		$usuario = '----';
		if ($consu->num_rows>0) {
			$rConsu = $consu->fetch_assoc();
			$usuario = $rConsu["usuario"];
		}
		$conexion->close();
		return $usuario;
	}

	public static function saber_metodo_pago($idMetodo=0){
		switch ($idMetodo) {
			case '1':
				return "Deposito bancario";
				break;
			
			default:
				// code...
				break;
		}		
	}

	public static function procesar_orden_compra($ordenes=array(), $opcion=0)	{
		foreach ($ordenes as $value) {			
			$idEncrip = self::formato_encript($value, "des");
			$idOrden = self::decriptTable($idEncrip);
			if(!empty($idOrden)){
				$ordenProcesada = self::validar_orden_procesada($idOrden);
				if(!$ordenProcesada){
					if ($opcion == '1') {#negar la orden
						$result = self::negar_orden_compra($idOrden);
						$mensaje = "Orden(es) negada(s)";
					}else if($opcion == '2'){#aprobar la orden
						$result = self::aprobar_orden_compra($idOrden);
						$mensaje = "Proceso de aprobación, ".$result["mensaje"];
					}
				}else{
					$result = false;
					$mensaje = "Esta orden ya ha sido procesada";
				}			
			}			
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	public static function validar_orden_procesada($idOrden){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM ordenes_compras WHERE id = $idOrden AND estado_proceso = '1'";
		$consu = $conexion->query($sql);
		$result = false;
		if($consu->num_rows>0)
			$result = true;
		
		$conexion->close();

		return $result;
	}

	private static function validar_orden_aprobada($idOrden){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM ordenes_compras WHERE id = $idOrden AND estado_aprobacion = '1'";
		$consu = $conexion->query($sql);
		$result = false;
		if($consu->num_rows>0)
			$result = true;
		
		$conexion->close();

		return $result;
	}
	
	private static function negar_orden_compra($idOrden){
		$result = false;
		if (!empty($idOrden)) {
			$sql = "UPDATE ordenes_compras SET estado_proceso = '1' WHERE id = $idOrden";
			$conexion = self::iniciar();
			if ($conexion->query($sql)) 
				$result = true;
			
			$conexion->close();
		}

		return $result;
	}

	private static function aprobar_orden_compra($idOrden){
		$result = false;
		if (!empty($idOrden)) {
			$sql = "UPDATE ordenes_compras SET estado_proceso = '1', estado_aprobacion = '1' WHERE id = $idOrden";
			$conexion = self::iniciar();
			if ($conexion->query($sql)) {
				$result = true;
				$result = self::generar_compra($idOrden);
			}
			$conexion->close();
		}

		return $result;
	}

	public static function consultar_orden_compra($idOrden){
		$sql = "SELECT numero_orden, id_usuario, total_orden_compra, total_descuento, total_impuesto, metodo_pago, fecha, estado_proceso, estado_aprobacion, soporte_pago, datos_envio, datos_facturacion FROM ordenes_compras WHERE id = $idOrden ";
		$conexion = self::iniciar();
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Orden no encontrada";
		if ($consu->num_rows>0) {
			$datos = $consu->fetch_array();
			$datos["result"] = true;
			$datos["mensaje"] = "";
		}
		$conexion->close();
		return $datos;
	}

	public static function consultar_orden_compra_detalle($idOrden){
		$conexion = self::iniciar();

		$sqlFac = "SELECT (SELECT usuario FROM usuarios AS u WHERE oc.id_usuario = u.id ) as usuario, datos_facturacion, datos_envio FROM ordenes_compras AS oc WHERE id = $idOrden ";
		$consu1 = $conexion->query($sqlFac);
		$rConsu1 = $consu1->fetch_assoc();
		$datosEnvio = $rConsu1["datos_envio"];
		$datosFacturacion = $rConsu1["datos_facturacion"];
		$usuario = $rConsu1["usuario"];
		$sql = "SELECT nombre_producto, precio_producto, impuesto_producto, descuento_producto, cantidad, precio_calculado, id_producto, orden_asociada FROM ordenes_compras_detalles WHERE ordenes_compras_id = $idOrden ";
		
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Orden detalle no encontrada";
		$datos["datos"] = array();
		if ($consu->num_rows>0) {
			while($data = $consu->fetch_array()){
				array_push($datos["datos"], $data);
			}
			$datos["result"] = true;
			$datos["mensaje"] = "";
			$datos["datos_envio"] = $datosEnvio;
			$datos["datos_facturacion"] = $datosFacturacion;
			$datos["usuario"] = (($usuario)?$usuario:'----');
		}
		$conexion->close();
		return $datos;
	}

	private static function generar_compra($idOrden){
		# consultamos la datos de la orden
		$datos = self::consultar_orden_compra($idOrden);
		if ($datos["result"]) {
			$orden = $datos["numero_orden"];
			$idComprador = $datos["id_usuario"];
			$totalOrdenCompra = $datos["total_orden_compra"];
			$totalDescuento = $datos["total_descuento"];
			$totalImpuesto = $datos["total_impuesto"];
			$metodoPago = $datos["metodo_pago"];
			$soportePago = $datos["soporte_pago"];
			$datosEnvio = $datos["datos_envio"];
			$datosFacturacion = $datos["datos_facturacion"];
			$fechaActual = self::fecha_sistema();
			# insertamos los datos si la orden existe
			$datosCompra = self::insertar_datos_compra($orden, $idComprador, $totalOrdenCompra, $totalDescuento, $totalImpuesto, $metodoPago, $soportePago, $datosEnvio, $datosFacturacion, $fechaActual);
			$datos["result"] = $datosCompra["result"];
			$datos["mensaje"] = $datosCompra["mensaje"];
			$nroCompra = $datosCompra["nro_compra"];
			# si los datos de la compra se guardan con exito se insertan los datos del detalle de la compra
			if ($datos["result"]) {
				#consultamos los datos del detalle de la orden de compra
				$datos = self::consultar_orden_compra_detalle($idOrden);
				if ($datos["result"]) {
					for ($i=0; $i <count($datos["datos"]) ; $i++) { 
						if(is_array($datos["datos"][$i])){							
							$nombreProducto 		= $datos["datos"][$i]["nombre_producto"];
							$precioProducto 		= $datos["datos"][$i]["precio_producto"];
							$impuestoProducto 		= $datos["datos"][$i]["impuesto_producto"];
							$descuentoProducto 		= $datos["datos"][$i]["descuento_producto"];
							$cantidad 				= $datos["datos"][$i]["cantidad"];
							$precioCalculado		= $datos["datos"][$i]["precio_calculado"];
							$idProducto 			= $datos["datos"][$i]["id_producto"];
							# insertamos el detalle de cada item de la orden
							$datosCompraDetalle 	= self::insertar_datos_compra_detalle($nombreProducto, $precioProducto, $impuestoProducto, $descuentoProducto, $cantidad, $precioCalculado, $idProducto, $nroCompra, $fechaActual);
						}
					}
					
					$datos["result"] = $datosCompraDetalle["result"];
					$datos["mensaje"] = $datosCompraDetalle["mensaje"];
				}
			}
		}

		return $datos;
	}

	private static function generar_nro_compra(){
		$conexion = self::iniciar();
		$sql = "SELECT IF (max(nro_compra) > 0, max(nro_compra), 0) AS ultimo_nro_compra FROM compras ";
		$consu  = $conexion->query($sql);
		$rConsu = $consu->fetch_assoc();
		$nroCompra = $rConsu["ultimo_nro_compra"];
		$conexion->close();
		return ++$nroCompra;
	}

	private static function insertar_datos_compra($orden, $idComprador, $totalOrdenCompra, $totalDescuento, $totalImpuesto, $metodoPago, $soportePago, $datosEnvio, $datosFacturacion, $fechaActual){
		$nroCompra = self::generar_nro_compra();#generamos el número de la compra
		$sql = "INSERT INTO compras (nro_compra, total_compra, total_descuento, total_impuesto, metodo_pago, fecha_compra, estado_envio, estado_aprobacion, estado_proceso, soporte_pago, id_usuario, datos_envio, datos_facturacion, orden_asociada) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$conexion = self::iniciar();
		$sentencia = $conexion->prepare($sql);
		$sentencia->bind_param('sdddssssssisss', $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9, $v10, $v11, $v12, $v13, $v14);
		$v1 = $nroCompra;
		$v2 = $totalOrdenCompra;
		$v3 = $totalDescuento;
		$v4 = $totalImpuesto;
		$v5 = $metodoPago;
		$v6 = $fechaActual;
		$v7 = '0';
		$v8 = '1';
		$v9 = '1';
		$v10 = $soportePago;
		$v11 = $idComprador;
		$v12 = $datosEnvio;
		$v13 = $datosFacturacion;
		$v14 = $orden;

		$result = true;
		$mensaje = "Datos de compra guardados";
		if (!$sentencia->execute()) {
			$result = false;
			$mensaje = "Error al insertar los datos de la compra ".$sentencia->error;
		}
		$conexion->close();
		return array("result"=>$result, "mensaje"=>$mensaje, "nro_compra" => $nroCompra);
	}

	private static function insertar_datos_compra_detalle($nombreProducto, $precioProducto, $impuestoProducto, $descuentoProducto, $cantidad, $precioCalculado, $idProducto, $nroCompra, $fechaActual){
		// echo "$nombreProducto, $precioProducto, $impuestoProducto, $descuentoProducto, $cantidad, $precioCalculado, $idProducto, $nroCompra";
		$conexion = self::iniciar();
		$consu = $conexion->query("SELECT id FROM compras WHERE nro_compra = '$nroCompra' LIMIT 1");
		$idCompra = 0;
		$result = false;
		$mensaje = "No se pudo agregar el detalle de la compra, no existe una compra para asociar";

		if($consu->num_rows>0){
			$rConsu = $consu->fetch_assoc();
			$idCompra = $rConsu["id"];
			$result = true;
		}

		if($result = true){
			$sql = "INSERT INTO compras_detalles (nombre, precio, cantidad, impuesto, descuento, fecha, id_producto, id_compra, precio_calculado) VALUES (?,?,?,?,?,?,?,?,?)";			
			$sentencia = $conexion->prepare($sql);
			$sentencia->bind_param('sdiddsiid', $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9);
			$v1 = $nombreProducto;
			$v2 = $precioProducto;
			$v3 = $cantidad;
			$v4 = $impuestoProducto;
			$v5 = $descuentoProducto;
			$v6 = $fechaActual;
			$v7 = $idProducto;
			$v8 = $idCompra;
			$v9 = $precioCalculado;

			$mensaje = "Datos de la compra guardados";
			if (!$sentencia->execute()) {
				$result = false;
				$mensaje = "Error al insertar los datos del detalle de la compra ".$sentencia->error;
			}
		}		
		$conexion->close();
		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	public static function cargar_detalle_orden_compra($idOrden){
		$idEncrip = $idOrden;
		$idOrden = Conexion::formato_encript($idOrden, "des");
		$idOrden = Conexion::decriptTable($idOrden);
		$tablaH = $tablaB = $tablaT = $datosComprador = '';
		$ordenAsociada = '----';	
		if (!empty($idOrden)) {
			$datos = self::consultar_orden_compra_detalle($idOrden);	
				
			if ($datos["result"]) {	
				$item = 0;	
				$totalImpuesto = $totalDescuento = $totalPrecioUni = $total = 0;
				$usuario 				= $datos["usuario"];	
				#datos de facturación
				$datosFacturacion 		= unserialize($datos["datos_facturacion"]);
				$nombreDireccionFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["nombre"]:$datosFacturacion["nombre"]));
				$telefonoDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["telefono"]:$datosFacturacion["telefono"]));
				$correoDirFac 			= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["correo"]:$datosFacturacion["correo"]));
				$direccionDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["direccion"]:$datosFacturacion["direccion"]));
				$identificacionDirFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["identificacion"]:$datosFacturacion["identificacion"]));
				$departamentoDirFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["departamento"]:$datosFacturacion["departamento"]));
				$municipioDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["municipio"]:$datosFacturacion["municipio"]));
				#datos de envío
				$datosEnvio 			= unserialize($datos["datos_envio"]);
				$nombreDireccionEnv 	= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["nombre"]:$datosEnvio["nombre"]));
				$telefonoDirEnv 		= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["telefono"]:$datosEnvio["telefono"]));
				$correoDirEnv 			= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["correo"]:$datosEnvio["correo"]));
				$direccionDirEnv 		= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["direccion"]:$datosEnvio["direccion"]));
				$identificacionDirEnv 	= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["identificacion"]:$datosEnvio["identificacion"]));
				$departamentoDirEnv 	= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["departamento"]:$datosEnvio["departamento"]));
				$municipioDirEnv 		= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["municipio"]:$datosEnvio["municipio"]));
				$ordenProcesada 		= self::validar_orden_procesada($idOrden);
				$ordenAprobada 			= self::validar_orden_aprobada($idOrden);
				$datosComprador = '
					<div class="col-lg-12">	
						'.((!$ordenProcesada)?'
						<form class="form-horizontal" id="formProcesarOrden">
							<div class="float-right">
									<div class="form-group">
										<label class="control-label">Negar orden(es)
											<input type="radio" id="negar-orden" name="opcion-orden" value="1" class="form-control">
										</label>
										<label class="control-label">Aprobar orden(es)
											<input type="radio" id="aprobar-orden" name="opcion-orden" value="2" class="form-control">
										</label>
										<input type="hidden" name="entrada" value="procesarOrdenCompra" class="form-control">
										<input type="hidden" name="autorizarOrden[]" id="autOrd'.$idEncrip.'" value="'.$idEncrip.'" data-control="'.$idEncrip.'" >
										<button type="submit" class="btn btn-info">Procesar selección</button>
									</div>
							</div>
						</form>
						':'
						'.(($ordenAprobada)?'
						<div class="float-right">						
							<div class="card bg-light text-black ">
								<div class="card-body">
									<div class="text-black small"><i class="btn btn-success btn-circle"><i class="fa fa-check"></i></i> Orden aprobada</div>
								</div>
							</div>
						</div>':'
						<div class="float-right">						
							<div class="card bg-light text-black ">
								<div class="card-body">
									<div class="text-black small"><i class="btn btn-danger btn-circle"><i class="fa fa-close"></i></i> Orden negada</div>
								</div>
							</div>
						</div>').'
						<div class="float-right">						
							<div class="card bg-light text-black ">
								<div class="card-body">
									<div class="text-black small"><i class="btn btn-success btn-circle"><i class="fa fa-check"></i></i> Orden procesada</div>
								</div>
							</div>
						</div>').'
					<h2>Usuario: <b>'.$usuario.'</b></h2>
					</div>
					<div class="col-lg-6">
						<h3>Datos facturación:</h3>
						<p>Identificación/Nit: '.$identificacionDirFac.'<br>
						Nombre de la dirección: '.$nombreDireccionFac.'	<br>
						Teléfono: '.$telefonoDirFac.'<br>
						Correo: '.$correoDirFac.'<br>
						Dirección: '.$direccionDirFac.'<br>
						Departamento: '.$departamentoDirFac.'<br>
						Municipio: '.$municipioDirFac.'</p>
						
					</div>
					<div class="col-lg-6">
						<h3>Datos Envío:</h3>
						<p>Identificación/Nit: '.$identificacionDirEnv.'<br>
						Nombre de la dirección: '.$nombreDireccionEnv.'	<br>
						Teléfono: '.$telefonoDirEnv.'<br>
						Correo: '.$correoDirEnv.'<br>
						Dirección: '.$direccionDirEnv.'<br>
						Departamento: '.$departamentoDirEnv.'<br>
						Municipio: '.$municipioDirEnv.'</p>	
					</div>
					<hr class="topbar topbar-divider" >
					';
				for ($i=0; $i <count($datos["datos"]) ; $i++) {
					$item++;
					$nombreProducto 		= $datos["datos"][$i]["nombre_producto"];
					$precioProducto 		= self::formato_decimal($datos["datos"][$i]["precio_producto"]);
					$precioBase 			= self::formato_decimal(($datos["datos"][$i]["precio_producto"]*$datos["datos"][$i]["cantidad"]));
					$impuestoProducto 		= self::formato_decimal($datos["datos"][$i]["impuesto_producto"]);
					$descuentoProducto 		= self::formato_decimal($datos["datos"][$i]["descuento_producto"]);
					$cantidad 				= $datos["datos"][$i]["cantidad"];
					$precioCalculado 		= self::formato_decimal($datos["datos"][$i]["precio_calculado"]);
					$ordenAsociada 			= $datos["datos"][$i]["orden_asociada"];
					$totalImpuesto 			+= $datos["datos"][$i]["impuesto_producto"];
					$totalDescuento 		+= $datos["datos"][$i]["descuento_producto"];
					$totalPrecioUni 		+= ($datos["datos"][$i]["precio_producto"]*$datos["datos"][$i]["cantidad"]);
					$total 					+= $datos["datos"][$i]["precio_calculado"];
					$tablaB .= "
						<tr>
							<td>$item</td>
							<td>$nombreProducto</td>
							<td>$precioProducto</td>
							<td>$precioBase</td>
							<td>$impuestoProducto</td>
							<td>$descuentoProducto</td>
							<td>$cantidad</td>
							<td>$precioCalculado</td>
						</tr>
					";
				}

				$result = true;
				$mensaje = '';
				$tablaH .= '				
				<div class="table-responsive">
					<table class="table table-striped table-condensed" width="100%" cellspacing="0">
						<thead>
							<tr class="bg-gradient-light">
								<th>Item</th>
								<th>Nombre producto</th>
								<th>Precio Unitario $ </th>
								<th>Precio Base $ </th>
								<th>Impuesto % </th>
								<th>Descuento % </th>
								<th>Cantidad M²</th>
								<th>Subtotal $ </th>
							</tr>
						</thead>
						<tbody>
							'.$tablaB.'
						</tbody>
					</table>
				</div>
				';

				$tablaT .= '
				<div class="table-responsive">
					<table class="table table-bordered" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="bg-gradient-light">Total Precio Unitario $ </th>
								<td>'.self::formato_decimal($totalPrecioUni).'</td>
							</tr>
							<tr>
								<th class="bg-gradient-light">Total Descuento % </th>
								<td>'.self::formato_decimal($totalDescuento).'</td>
							</tr>
								<th class="bg-gradient-light">Total Impuesto % </th>	
								<td>'.self::formato_decimal($totalImpuesto).'</td>
							<tr>
								<th class="bg-gradient-light">Total Orden Compra $ </th>
								<td>'.self::formato_decimal($total).'</td>
							</tr>
						</thead>
					</table>
				</div>
				';
			}else{
				$result = $datos["result"];
				$mensaje = $datos["mensaje"];
				$tablaH .= ' <i class="fa fa-close text-danger" ></i> No se pudo cargar el contenido de la orden. ';
			}
			
		}else{
			$result = false;
			$mensaje = "No fue posible reconocer el detalle de la orden seleccionada ";
			$tablaH .= ' <i class="fa fa-close text-danger" ></i> Orden no reconocida. ';
		}

		$html = '
				<div class="card shadow mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<p>Detalle de la Orden <b>'.$ordenAsociada.'</b> </p><a href="lista-orden-compras" class="btn btn-warning float-right" id="regDirBtnSel"><i class="fa fa-arrow-circle-left"></i> Volver</a>
					</div>
					
					<div class="card-body">
						<div class="row ">
							'.$datosComprador.'
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							'.$tablaH.'
							</div>							
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div style="float:right">
									'.$tablaT.'
								</div>
							</div>
						</div>
					</div>
					
				</div>
			';

		return array("result"=>$result, "mensaje"=>$mensaje, "html"=>$html);
	}
}
?>