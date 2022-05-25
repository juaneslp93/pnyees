<?php 
/**
 * LOGIN
 */
class Pagos Extends Conexion
{
	
	function __construct(){
		# code...
	}

	public static function consultarSoportePagoExiste($soporte=''){
		$conexion = self::iniciar();
		$sql = "SELECT id FROM ordenes_compras WHERE soporte_pago = '$soporte'";
		$consu = $conexion->query($sql);
		$result = false;
		if ($consu->num_rows>0) {
			$result = true;
		}
		$conexion->close();
		return $result;
	}

	public static function cargarDatosFacturacion($id = 0){
		$where = (($id>0)? ' id = '.$id:' usuarios_id = '.self::desencriptar($_SESSION["TIENDA"]["ID"], "Tbl1"));
		$conexion = self::iniciar();
		$sql = "SELECT id, nombre, telefono, correo, direccion, identificacion, (SELECT nombre FROM departamentos AS d WHERE departamento = d.codigo LIMIT 1) AS departamento, (SELECT nombre_municipio FROM municipios AS m WHERE municipio = m.codigo_municipio LIMIT 1) AS municipio FROM usuarios_direcciones WHERE $where ";
		$consu = $conexion->query($sql);
		$datos = array();
		if ($consu->num_rows>0) {
			$res = true;
			while ($rConsu = $consu->fetch_assoc()) {
				array_push($datos, array(
					"result" => true, 
					"id" => $rConsu["id"], 
					"nombre" => $rConsu["nombre"], 
					"telefono" => $rConsu["telefono"], 
					"correo" => $rConsu["correo"], 
					"direccion" => $rConsu["direccion"], 
					"identificacion" => $rConsu["identificacion"], 
					"departamento" => $rConsu["departamento"], 
					"municipio" => $rConsu["municipio"]
				));
			}
		}else{
			$res = false;
			$datos = array(
					"result" => false, 
					"id" => '', 
					"nombre" => '', 
					"telefono" => '', 
					"correo" => '', 
					"direccion" => '', 
					"identificacion" => '', 
					"departamento" => '', 
					"municipio" => ''
				);
		}
		$conexion->close();
		return array("result"=>$res, "datos"=>$datos);
	}

	public static function generarHtmlDirecciones($datos=array()){
		$html = '<div class="card shadow mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<p> Seleccione uno de los datos de facturación registrados para continuar </p><button type="button" class="btn btn-info float-right" id="regDirBtnSel">Registrar dirección</button>
			</div>
			<div class="card-body">
				<form  name="formDireccionSelect" id="formDireccionSelect" accept-charset="utf-8" class="was-validated">				
												
		';
		if (!empty($datos)) {
			$i=0;
			foreach ($datos as $array) {				
				foreach ($array as $key => $value) {
					if ($key === "id") {
						$i++;
						$html .= '
							<div class="bg-gradient-light">															
								<label class="switch float-right">
									<input type="radio" name="'.$key.'" value="'.$value.'" >
									<span class="slider round"></span>
								</label>
								<a class="btn btn-default" data-toggle="collapse" href="#collapseExample'.$i.'" aria-expanded="false" aria-controls="collapseExample'.$i.'">
									<i class=" fa fa-arrow-alt-circle-down fa-lg"></i> Dirección '.$i.'
								</a>
							</div>
						';
					}else if($key !== "result"){

						$html .= '
							<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
								<div class="collapse" id="collapseExample'.$i.'">
									<div class="form-group">
										<label class="control-label" >
										'.strtoupper($key).'
										</label>
										<label class="form-control" >
										'.$value.'
										</label>
										
									</div>
								</div>
							</div>	
						';					
					}
				}
				$html .= ' <br>';
			}
		}

		$html .= '	<input type="hidden" name="entrada" value="selectDireccionFact">
					<input type="submit" name="btnformDireccionSelect" value="Continuar" class="btn btn-success">
				</form>
			</div>
		</div>
		<script type="text/javascript" charset="utf-8" async defer>
			$("#regDirBtnSel").on("click", function(){
				procesoPagos.cargar_datos_facturacion("regi");
			});
		</script>';
		return $html;
	}

	public static function generar_orden_deposito_bancario($cuenta='', $soporte='', $fechaSoporte=''){
		
		$orden = self::generar_numero_orden();
		$fecha = self::fecha_sistema();
		$idComprador = self::desencriptar($_SESSION["TIENDA"]["ID"], "Tbl1");

		#calculamos los totales de la compra
		$totales = self::totales_compra();
		$totalCompra = $totales["totalCompra"];
		$totalImpuesto = $totales["totalImpuesto"];
		$totalDescuento = $totales["totalDescuento"];
		$error = false;		
		$result = true;
		$mensaje = "Orden de compra realizada";
		#se registra la orden en la base de datos
		$registroOrden = self::registrar_orden_compra($idComprador, $orden, $soporte, $cuenta, $fechaSoporte, $totalCompra, $totalDescuento, $totalImpuesto, 1, $fecha );

		# si la insersión de la orden fue exitosa, registramos el detalle de la orden
		if ($registroOrden["id"]>0) {
			for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) {
				if (!empty($_SESSION["CARRITO"][$i]["id_producto"])) {
					$idProductoEncrip = self::formato_encript($_SESSION["CARRITO"][$i]["id_producto"], "des");
					$idProducto = self::desencriptar($idProductoEncrip, "Det1");
					$nombre 				= $_SESSION["CARRITO"][$i]["nombre"];
					$precio 				= $_SESSION["CARRITO"][$i]["precio"];
					$impuesto 				= $_SESSION["CARRITO"][$i]["impuesto"];
					$descuento 				= $_SESSION["CARRITO"][$i]["descuento"];
					$cantidad 				= $_SESSION["CARRITO"][$i]["cantidad"];
					$descripcion 			= $_SESSION["CARRITO"][$i]["descripcion"];
					$imagen 				= $_SESSION["CARRITO"][$i]["imagen"];
					$precioCalculado 		= $_SESSION["CARRITO"][$i]["precio_calculado"];

					$registroDetalle = self::registrar_orden_detalle_compra($registroOrden["id"], $idProducto, $nombre, $precio, $impuesto, $descuento, $cantidad, $descripcion, $imagen, $precioCalculado, $fecha, $orden);
					
					if ($registroDetalle["error"]) {
						$error = true;
						$mensaje = "Error al insertar el detalle de la orden (".$registroDetalle["mensaje"].")";
						break;
					}
				}
			}

			if (!$error) {
				unset($_SESSION["CARRITO"]);
			}
		}else{
			$error = true;
			$mensaje = "Error al insertar la orden";
		}

		if ($error) 
			$result = false;
		
		return array("result"=>$result, "mensaje" => $mensaje);
		/*array(1) {
  [0]=>
  array(9) {
    ["id_producto"]=>
    string(4) "ZQ--"
    ["nombre"]=>
    string(7) "Prueba2"
    ["precio"]=>
    string(7) "1900.00"
    ["impuesto"]=>
    string(5) "19.00"
    ["descuento"]=>
    float(0)
    ["cantidad"]=>
    int(699)
    ["descripcion"]=>
    string(7) "asdfads"
    ["imagen"]=>
    string(44) "3381115f3bee4efad599a1a8e3f340556c127e0f.jpg"
    ["precio_calculado"]=>
    float(902139)
  }
}*/
		// $idProducto = self::formato_encript($_SESSION["CARRITO"], "des");
		// echo $idProducto = self::desencriptar($idProductoEncrip, "Det1");
		// var_dump($_SESSION["TIENDA"]);
		// var_dump($_SESSION["DATOS_FACTURACION"]);
	}

	private static function totales_compra(){
		$totalCompra = 0;
		$totalImpuesto = 0;
		$totalDescuento = 0;
		if (!empty($_SESSION["CARRITO"])) {
			for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) {
				if (!empty($_SESSION["CARRITO"][$i]["id_producto"])) {
					$totalCompra += $_SESSION["CARRITO"][$i]["precio_calculado"];
					$totalImpuesto += $_SESSION["CARRITO"][$i]["impuesto"];
					$totalDescuento += $_SESSION["CARRITO"][$i]["descuento"];
				}
			}
		}

		return array(
			"totalCompra" => $totalCompra,
			"totalImpuesto" => $totalImpuesto,
			"totalDescuento" => $totalDescuento
		);
	}

	private static function registrar_orden_compra($idComprador, $orden, $soportePago, $cuentaPago, $fechaSoporte, $totalCompra, $totalDescuento, $totalImpuesto, $metodoPago, $fecha){
		$conexion = self::iniciar();
		$sql = "INSERT INTO ordenes_compras (numero_orden, total_orden_compra, total_descuento, total_impuesto, metodo_pago, fecha, estado_proceso, estado_aprobacion, soporte_pago, datos_envio, datos_facturacion, id_usuario) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ";
		$sentencia = $conexion->prepare($sql);		
		$sentencia->bind_param('sdddissssssi', $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9, $v10, $v11, $v12);
		$v1 =  $orden;
		$v2 =  $totalCompra;
		$v3 =  $totalDescuento;
		$v4 =  $totalImpuesto;
		$v5 =  $metodoPago;
		$v6 =  $fecha;
		$v7 =  '0';
		$v8 =  '0';
		$v9 =  $soportePago;
		$v10 = $_SESSION["DATOS_FACTURACION"];
		$v11 = $_SESSION["DATOS_FACTURACION"];
		$v12 = $idComprador;

		$mensaje = '';
		$id = 0;
		if (!$sentencia->execute()) 
			$mensaje = "Error al guardar la dirección ";

		# consultamos el ultimo registro hecho por el usuario para retornar la continuación del proceso
		$consu = $conexion->query("SELECT max(id) AS id FROM ordenes_compras WHERE id_usuario = $idComprador ");
		if ($consu->num_rows>0) {
			$rConsu = $consu->fetch_assoc();
			$id = $rConsu["id"];
		}
		$conexion->close();
		return array("id"=>$id, "mensaje"=>$mensaje);
	}

	private static function registrar_orden_detalle_compra($registroOrdenId, $idProducto, $nombre, $precio, $impuesto, $descuento, $cantidad, $descripcion, $imagen, $precioCalculado, $fecha, $ordenAsociada){
		$conexion = self::iniciar();
		$sql = "INSERT INTO ordenes_compras_detalles (ordenes_compras_id, id_producto, nombre_producto, precio_producto, impuesto_producto, descuento_producto, cantidad, precio_calculado, orden_asociada, fecha) VALUES (?,?,?,?,?,?,?,?,?,?) ";
		$sentencia = $conexion->prepare($sql);		
		$sentencia->bind_param('iisdddidss', $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9, $v10);
		$v1 =  $registroOrdenId;
		$v2 =  $idProducto;
		$v3 =  $nombre;
		$v4 =  $precio;
		$v5 =  $impuesto;
		$v6 =  $descuento;
		$v7 =  $cantidad;
		$v8 =  $precioCalculado;
		$v9 =  $ordenAsociada;
		$v10 = $fecha;

		$error = false;
		$mensaje = '';
		if (!$sentencia->execute()) 
			$error = true;
		
		if ($error) 
			$mensaje = $sentencia->error;
		
		$conexion->close();
		return array("error"=>$error, "mensaje"=>$mensaje);
	}

	public static function generar_html_registro_direccion(){
		$contenido = '
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<p> Registre los datos de facturación requeridos </p><button type="button" class="btn btn-warning float-right" id="regDirBtnSel"><i class="fa fa-arrow-circle-left"></i> Volver</button>
				</div>
				<form  name="formDireccionCrear" id="formDireccionCrear" accept-charset="utf-8" class="was-validated">
					<div class="card-body">
						<div class="form-group">
							<label class="control-label" >Nombre de la direción</label>
							<input type="text" class="form-control" name="nombre" required />
						</div>
						<div class="form-group">
							<label class="control-label" >Identificación/Nit</label>
							<input type="text" class="form-control" name="identificacion" required />
						</div>
						<div class="form-group">
							<label class="control-label" >Direccción</label>
							<input type="text" class="form-control" name="direccion" required />
						</div>
						<div class="form-group">
							<label class="control-label" >Teléfono</label>
							<input type="number" class="form-control" name="telefono" required />
						</div>
						<div class="form-group">
							<label class="control-label" >Correo</label>
							<input type="mail" class="form-control" name="correo" required />
						</div>
						'.self::cargar_departamentos().'
					</div>
					<div class="card-footer">
						<div class="form-group">
							<input type="hidden" name="entrada" value="registrarDireccion">
							<button type="submit" class="btn btn-success">Guardar la dirección</button>
						</div>
					</div>
				</form>
			</div>
			<script type="text/javascript" charset="utf-8" async defer>
				$("#formDireccionCrear").submit(function(event){
					event.preventDefault();
					var codigo = $(this).val();
					$.ajax({
						url: "controller/ctr_pagos.php",
						type: "POST",
						dataType: "json",
						data:$(this).serialize(),
					})
					.done(function(result) {
						if(result.continue){
							Swal.fire({
							  icon: "success",
							  title: "¡Proceso exitoso!",
							  text: result.mensaje
							});
							$("#direccionModal").modal("hide");
							$("body").removeClass("modal-open");//eliminamos la clase del body para poder hacer scroll
							$(".modal-backdrop").remove();//eliminamos el backdrop del modal
							document.getElementById("formDireccionCrear").reset();
						}else{
							Swal.fire({
							  icon: "warning",
							  title: "¡Proceso detenido!",
							  text: result.mensaje
							});
						}
					})
					.fail(function() {
						console.log("error");
					});
				});

				$("#regDirBtnSel").on("click", function(){
					procesoPagos.cargar_datos_facturacion();
				});
			</script>
		';
		return $contenido;
	}

	public static function registrar_direccion($nombre='', $telefono='', $correo='', $direccion='', $identificacion='', $departamento='', $municipio=''){
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		// si existe una sesión iniciada se realizará la validación de registro de dirección en el sistema //
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$idDireccion = 0;
		if (isset($_SESSION["SYSTEM"]["TIPO"])) {
			
			$idDireccion = self::registro_direccion($nombre, $telefono, $correo, $direccion, $identificacion, $departamento, $municipio, $idDireccion);
		}
		$datosDireccion = array("result" => true, 
					"id" => $idDireccion, 
					"nombre" => $nombre, 
					"telefono" => $telefono, 
					"correo" => $correo, 
					"direccion" => $direccion, 
					"identificacion" => $identificacion, 
					"departamento" => $departamento, 
					"municipio" => $municipio
				);
		$_SESSION["DATOS_FACTURACION"] = serialize($datosDireccion);
		return array('result' => true, 'mensaje'=> "Dirección cargada");
	}

	private static function registro_direccion($nombre, $telefono, $correo, $direccion, $identificacion, $departamento, $municipio, $idDireccion){
		$idComprador = $_SESSION["TIENDA"]["ID"];
		$idComprador = self::desencriptar($idComprador, "Tbl1");
		$conexion = self::iniciar();
		$sql = "INSERT INTO usuarios_direcciones (usuarios_id, nombre, telefono, correo, direccion, identificacion, departamento, municipio, fecha, estado) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$sentencia = $conexion->prepare($sql);
		$sentencia->bind_param('isssssssss', $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9, $v10);
		$v1 =  $idComprador;
		$v2 =  $nombre;
		$v3 =  $telefono;
		$v4 =  $correo;
		$v5 =  $direccion;
		$v6 =  $identificacion;
		$v7 =  $departamento;
		$v8 =  $municipio;
		$v9 =  self::fecha_sistema();
		$v10 =  '1';

		if (!$sentencia->execute()) 
			$mensaje = "Error al guardar la dirección ";
		
		$sel = "SELECT max(id) AS id FROM usuarios_direcciones WHERE usuarios_id = $idComprador ";
		$consu = $conexion->query($sel);
		if ($consu->num_rows>0) {
			$rConsu = $consu->fetch_assoc();
			$idDireccion = $rConsu["id"];
		}

		$conexion->close();
		return $idDireccion;
	}
}
?>
