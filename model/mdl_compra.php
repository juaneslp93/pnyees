<?php 
/**
 * 
 */
class Compras extends Conexion
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

	public static function procesar_compra($compras=array(), $opcion=0)	{
		$_SESSION["GENERAR_PDF_ENVIO"] = array();
		foreach ($compras as $value) {			
			$idEncrip = self::formato_encript($value, "des");
			$idCompra = self::decriptTable($idEncrip);
			if(!empty($idCompra)){				
				if ($opcion == '1') {#anular la compra
					$result = self::anular_compra($idCompra);
					$mensaje = "Compra(s) anulada(s)";
				}else if($opcion == '2'){#enviar la compra
					$compraEnviada = self::validar_compra_enviada($idCompra);
					if(!$compraEnviada){
						$datos = self::aprobar_compra_envio($idCompra, $idEncrip);
						$result = $datos["result"];
						$mensaje = $datos["mensaje"];
					}else{
						$result = false;
						$mensaje = "Esta compra ya ha sido enviada";
					}	
				}			
			}			
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	public static function validar_compra_enviada($idCompra){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM compras WHERE id = $idCompra AND estado_envio = '1'";
		$consu = $conexion->query($sql);
		$result = false;
		if($consu->num_rows>0)
			$result = true;
		
		$conexion->close();

		return $result;
	}

	private static function validar_compra_aprobada($idCompra){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM compras WHERE id = $idCompra AND estado_aprobacion = '1'";
		$consu = $conexion->query($sql);
		$result = false;
		if($consu->num_rows>0)
			$result = true;
		
		$conexion->close();

		return $result;
	}
	
	private static function anular_compra($idCompra){
		$result = false;
		if (!empty($idCompra)) {
			$sql = "UPDATE compras SET estado_aprobacion = '0' WHERE id = $idCompra";
			$conexion = self::iniciar();
			if ($conexion->query($sql)) 
				$result = true;
			
			$conexion->close();
		}

		return $result;
	}

	private static function aprobar_compra_envio($idCompra, $idEncrip){
		
		if (!empty($idCompra)) {
			$sql = "UPDATE compras SET estado_envio = '1' WHERE id = $idCompra";
			$conexion = self::iniciar();
			if ($conexion->query($sql)) {
				$result = true;
				$mensaje = "Estado de envío actualizado ";
				self::consultar_datos_envio_activo($idEncrip);
			}else{
				$result = false;
				$mensaje = $conexion->error;
			}
			$conexion->close();
		}

		return array("result"=>$result, "mensaje"=>$mensaje);
	}

	private static function consultar_datos_envio_activo($idEncrip){
		$idCompra = Conexion::formato_encript($idEncrip, "des");
		echo $idCompra = Conexion::decriptTable($idCompra);
	
		if (!empty($idCompra)) {
			$datos = self::consultar_compra_detalle($idCompra);
		}
		
		if ($datos["result"]) {
			array_push($_SESSION["GENERAR_PDF_ENVIO"], $datos);	
		}
	}

	private static function consultar_compra($idCompra){
		$sql = "SELECT nro_compra, id_usuario, total_compra, total_descuento, total_impuesto, metodo_pago, fecha, estado_proceso, estado_aprobacion, soporte_pago, datos_envio, datos_facturacion FROM compras WHERE id = $idCompra ";
		$conexion = self::iniciar();
		$consu = $conexion->query($sql);
		$datos["result"] = false;
		$datos["mensaje"] = "Compra no encontrada";
		if ($consu->num_rows>0) {
			$datos = $consu->fetch_array();
			$datos["result"] = true;
			$datos["mensaje"] = "";
		}
		$conexion->close();
		return $datos;
	}

	private static function consultar_compra_detalle($idCompra){
		$conexion = self::iniciar();

		$sqlFac = "SELECT (SELECT telefono FROM usuarios AS u WHERE oc.id_usuario = u.id ) as telefono, (SELECT correo FROM usuarios AS u WHERE oc.id_usuario = u.id ) as correo, (SELECT CONCAT(nombre, ' ', apellido) FROM usuarios AS u WHERE oc.id_usuario = u.id ) as nombre_completo, (SELECT usuario FROM usuarios AS u WHERE oc.id_usuario = u.id ) as usuario, datos_facturacion, datos_envio, nro_compra FROM compras AS oc WHERE id = $idCompra ";
		$consu1 = $conexion->query($sqlFac);
		$rConsu1 = $consu1->fetch_assoc();
		$datosEnvio = $rConsu1["datos_envio"];
		$datosFacturacion = $rConsu1["datos_facturacion"];
		$nroCompra = $rConsu1["nro_compra"];
		$usuario = $rConsu1["usuario"];
		$nombreCompleto = $rConsu1["nombre_completo"];
		$correo = $rConsu1["correo"];
		$telefono = $rConsu1["telefono"];
		$sql = "SELECT nombre, precio, impuesto, descuento, cantidad, precio_calculado, id_producto FROM compras_detalles WHERE id_compra = $idCompra ";
		
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
			$datos["nombre_completo"] = (($nombreCompleto)?$nombreCompleto:'----');
			$datos["nro_compra"] = self::formato_nro_factura($nroCompra);
			$datos["correo"] = (($correo)?$correo:'----');
			$datos["telefono"] = (($telefono)?$telefono:'----');
		}
		$conexion->close();
		return $datos;
	}

	public static function cargar_detalle_compra($idCompra){
		$idEncrip = $idCompra;
		$idCompra = Conexion::formato_encript($idCompra, "des");
		$idCompra = Conexion::decriptTable($idCompra);
		$tablaH = $tablaB = $tablaT = $datosComprador = '';
		$nroCompra = '----';	
		if (!empty($idCompra)) {
			$datos = self::consultar_compra_detalle($idCompra);
				
			if ($datos["result"]) {	
				$item = 0;	
				$totalImpuesto = $totalDescuento = $totalPrecioUni = $total = 0;
				$usuario 				= $datos["usuario"];
				$nroCompra 				= $datos["nro_compra"];	
				$nombreCompleto			= $datos["nombre_completo"];	
				$correo 				= $datos["correo"];	
				$telefono 				= $datos["telefono"];	
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
				$compraEnviada 			= self::validar_compra_enviada($idCompra);
				$datosComprador = '
					<div class="col-lg-12">	
						'.(($compraEnviada)?'
						<div class="float-right">						
							<div class="card bg-light text-black ">
								<div class="card-body">
									<div class="text-black small"><i class="btn btn-success btn-circle btn-lg"><i class="fa fa-check"></i></i> Enviada</div>
								</div>
							</div>
						</div>':'
						<div class="float-right">						
							<div class="card bg-light text-black ">
								<div class="card-body">
									<div class="text-black small"><i class="btn btn-danger btn-circle btn-lg"><i class="fa fa-close"></i></i> NO enviada</div>
								</div>
							</div>
						</div>').'
						<h3>Datos comprador:</h3>
						<p>
							<h4>Usuario: <b>'.$usuario.'</b></h4>
							Nombre Completo: '.$nombreCompleto.'<br>
							Teléfono: '.$telefono.'<br>
							Correo: '.$correo.'<br>
						</p>
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
					$nombreProducto 		= $datos["datos"][$i]["nombre"];
					$precioProducto 		= self::formato_decimal($datos["datos"][$i]["precio"]);
					$precioBase 			= self::formato_decimal(($datos["datos"][$i]["precio"]*$datos["datos"][$i]["cantidad"]));
					$impuestoProducto 		= self::formato_decimal($datos["datos"][$i]["impuesto"]);
					$descuentoProducto 		= self::formato_decimal($datos["datos"][$i]["descuento"]);
					$cantidad 				= $datos["datos"][$i]["cantidad"];
					$precioCalculado 		= self::formato_decimal($datos["datos"][$i]["precio_calculado"]);					
					$totalImpuesto 			+= $datos["datos"][$i]["impuesto"];
					$totalDescuento 		+= $datos["datos"][$i]["descuento"];
					$totalPrecioUni 		+= ($datos["datos"][$i]["precio"]*$datos["datos"][$i]["cantidad"]);
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
					<table class="table table-bordered" width="100%" cellspacing="0">
						<thead>
							<tr>
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
								<th>Total Precio Unitario $ </th>
								<td>'.self::formato_decimal($totalPrecioUni).'</td>
							</tr>
							<tr>
								<th>Total Descuento % </th>
								<td>'.self::formato_decimal($totalDescuento).'</td>
							</tr>
								<th>Total Impuesto % </th>	
								<td>'.self::formato_decimal($totalImpuesto).'</td>
							<tr>
								<th>Total Compra $ </th>
								<td>'.self::formato_decimal($total).'</td>
							</tr>
						</thead>
					</table>
				</div>
				';
			}else{
				$result = $datos["result"];
				$mensaje = $datos["mensaje"];
				$tablaH .= ' <i class="fa fa-close text-danger" ></i> No se pudo cargar el contenido de la compra. ';
			}
			
		}else{
			$result = false;
			$mensaje = "No fue posible reconocer el detalle de la compra seleccionada ";
			$tablaH .= ' <i class="fa fa-close text-danger" ></i> Compra no reconocida. ';
		}

		$html = '
				<div class="card shadow mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<p>Detalle de la Compra Nro <b>'.$nroCompra.'</b> </p><a href="javascript:Detalle_compra.generar_pdf(\''.$idEncrip.'\')" class="btn btn-info float-right"><i class="fa fa-file-pdf"></i> Generar factura </a><a href="lista-compras" class="btn btn-warning float-right" id="regDirBtnSel"><i class="fa fa-arrow-circle-left"></i> Volver</a>
					</div>
					
					<div class="card-body">
						<div class="row ">
							'.$datosComprador.'
							<div class="col-lg-8">
							'.$tablaH.'
							</div>
							<div class="col-lg-4">
							'.$tablaT.'
							</div>
						</div>
					</div>
					
				</div>
			';

		return array("result"=>$result, "mensaje"=>$mensaje, "html"=>$html);
	}

	public static function cargar_pdf_factura($idEncrip){
		$_SESSION["GENERAR_PDF_ENVIO"] = array();
		self::consultar_datos_envio_activo($idEncrip);
	}
}
?>