<?php 
/**
 * 
 */
class Catalogo extends Conexion
{
	
	function __construct()	{
		# code...
	}

	public static function cargar_catalogo()	{
		$conexion = self::iniciar();
		$sql = "SELECT id, nombre, precio, impuesto, url_imagen FROM productos WHERE estado='1' ORDER BY id DESC ";
		$consu = $conexion->query($sql);
		$cont = '<div class="row">
		<style type="text/css" media="screen">
			.form-control-feedback {
			  position: absolute;
			  padding: 10px;
			  pointer-events: none;
			}

			.form-control {
			  padding-left: 50px!important;
			}
		</style>';
		if ($consu->num_rows>0) {
			$datosConfig = self::consulta_datos_config();
			while ($rConsu = $consu->fetch_assoc()) {
				$precio = ($rConsu["precio"]*$rConsu["impuesto"])/100;
				$precio = $precio+$rConsu["precio"];
				$precio = number_format($precio, $datosConfig["cantidad_decimales"], ',','.');
				$idProductoEncrip = self::encriptar($rConsu["id"], "Det1");
				$idProductoEncrip = self::formato_encript($idProductoEncrip, "con");
				$ruta = URL_ABSOLUTA."uploads/";
				$imagen = $ruta.((!empty($rConsu["url_imagen"]))?$rConsu["url_imagen"]:'default.png');
				$cont .= '<div class="col-lg-3 col-md-6 col-sm-12">
		                    <div class="card shadow mb-4">
		                        <div class="card-body">
		                            <div class="row ">
		                            	<div class="col-lg-12">
		                            		<form method="post" name="FormAgregarCarrito" class="agregarCarrito" accept-charset="utf-8">
			                            		<a href="detalle-'.$idProductoEncrip.'" ><img src="'.$imagen.'" class="img-responsive img-rounded img-fluid" style="max-height: 300px;" alt="">
			                            		</a>
			                            		<h3>'.$rConsu["nombre"].'</h3>
			                            		'.$datosConfig["simbolo_moneda"].$precio.'<br>
			                            		<div class="form-group has-feedback">
												  <i class="fa form-control-feedback">'.$datosConfig["simbolo_cantidad"].' </i>
												  <input type="number" name="cantidad" class="form-control" placeholder="Seleccione su cantidad" min="1" pattern="^[0-9]+">
												  <input type="hidden" name="data-control" value="'.$idProductoEncrip.'" >
												  <input type="hidden" name="entrada" value="agregarProducto" >
												</div>
												<div class="row">
													<div class="col-sm-6">
														<button type="submit" class="btn btn-info btn-sm xs"><i class="fa fa-plus"></i> Agregar</button>
													</div>
													<div class="col-sm-6">
														<a href="detalle-'.self::encriptar($rConsu["id"], "Det1").'" class="btn btn-warning btn-sm"><i class="fa fa-external-link"></i> Ver detalle</a>
													</div>
												</div>
			                            	</form>
		                            	</div>
		                            </div>
		                        </div>
		                    </div>                    		
                    	</div>';
			}
		}else{
			$cont .= '<div class="col-lg-12 col-md-12 col-sm-12">
		                    <div class="card shadow mb-4 align-self-center text-center">
		                        <div class="card-body">
		                            <div class="row justify-content-center">
		                            	<div class="col-lg-12">
		                            		Sin resultados
		                            	</div>
		                            </div>
		                        </div>
		                    </div>                    		
                    	</div><br>';
		}
		$conexion->close();
		$cont .= '</div>';
		return $cont;
	}

	public static function cargar_detalle_producto($idProductoEncrip=0)	{
		$idProducto = self::formato_encript($idProductoEncrip, "des");
		$idProducto = self::desencriptar($idProductoEncrip, "Det1");
		$conexion = self::iniciar();		
		$sql = "SELECT id, nombre, precio, impuesto, url_imagen, descripcion FROM productos WHERE estado='1' AND id=$idProducto ORDER BY id DESC ";
		$consu = $conexion->query($sql);
		$cont = '<div class="row">
		<style type="text/css" media="screen">
			.form-control-feedback {
			  position: absolute;
			  padding: 10px;
			  pointer-events: none;
			}

			.form-control {
			  padding-left: 50px!important;
			}
		</style>';
		if ($consu->num_rows>0) {
			$datosConfig = self::consulta_datos_config();
			$rConsu = $consu->fetch_assoc();
			$precio = ($rConsu["precio"]*$rConsu["impuesto"])/100;
			$precio = $precio+$rConsu["precio"];
			$precio = number_format($precio, $datosConfig["cantidad_decimales"], ',','.');
			$ruta = URL_ABSOLUTA."uploads/";
			$imagen = $ruta.((!empty($rConsu["url_imagen"]))?$rConsu["url_imagen"]:'default.png');
			$cont .= '<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card shadow mb-12">
							<div class="card-body">
								<div class="row ">
									<div class="col-lg-12">
										<form method="post" name="FormAgregarCarrito" class="agregarCarrito" accept-charset="utf-8">
											<img src="'.$imagen.'" class="img-responsive img-rounded img-fluid" style="width: 35rem;" alt="">

											<h3>'.$rConsu["nombre"].'</h3>
											<h6>'.$rConsu["descripcion"].'</h6>
											'.$datosConfig["simbolo_moneda"].$precio.'<br>
											<div class="form-group has-feedback row col-lg-3">
												<i class="fa form-control-feedback">'.$datosConfig["simbolo_cantidad"].' </i>
												<input type="number" name="cantidad" class="form-control" placeholder="Seleccione su cantidad" min="1" pattern="^[0-9]+">
												<input type="hidden" name="data-control" value="'.$idProductoEncrip.'" >
												<input type="hidden" name="entrada" value="agregarProducto" >
											</div>
											<button type="submit" class="btn btn-info "><i class="fa fa-plus"></i> Agregar al carrito</button>
										</form>
									</div>
								</div>
							</div>
						</div>                    		
					</div>';
			
		}else{
			$cont .= '<div class="col-lg-12 col-md-12 col-sm-12">
		                    <div class="card shadow mb-4 align-self-center text-center">
		                        <div class="card-body">
		                            <div class="row justify-content-center">
		                            	<div class="col-lg-12">
		                            		Sin resultados
		                            	</div>
		                            </div>
		                        </div>
		                    </div>                    		
                    	</div><br>';
		}
		$conexion->close();
		$cont .= '</div>';
		return $cont;
	}

	public static function agregar_producto($cantidad='', $idProductoEncrip='')	{
		$idProducto = self::formato_encript($idProductoEncrip, "des");
		$idProducto = self::desencriptar($idProducto, "Det1");
		$result = array("result"=>false, "mensaje"=>"Error al agregar el producto");
		$existe = false;
		
		if (!empty($_SESSION["CARRITO"])) {
			for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) {
				if ($_SESSION["CARRITO"][$i]["id_producto"] === $idProductoEncrip) {
					$_SESSION["CARRITO"][$i]["cantidad"] += $cantidad;
					$existe = true;
					$result = array("result"=>true, "mensaje"=>"producto actualizado");
					break;
				}
			}
		}
		
		if (!$existe) {
			$result = self::consultar_producto($cantidad, $idProducto, $idProductoEncrip);
		}
		return $result;
	}

	private static function consultar_producto($cantidad, $idProducto, $idProductoEncrip){
		$conexion = self::iniciar();
		$sql = "SELECT nombre, descripcion, precio, impuesto, url_imagen FROM productos WHERE id= $idProducto AND estado='1'";
		$consu = $conexion->query($sql);
		if (!isset($_SESSION["CARRITO"])) {
			$_SESSION["CARRITO"] = array();
		}
		if ($consu->num_rows>0) {
			$rConsu = $consu->fetch_assoc();
			$descuento = self::obtener_descuento($cantidad, $idProducto);
			$result = array("result"=>true, "mensaje"=>"producto agregado");
			$precio_calculado = self::calcular_precio($cantidad, $rConsu["precio"], $descuento, $rConsu["impuesto"]);

			array_push($_SESSION["CARRITO"], 
				array("id_producto" => $idProductoEncrip,
					"nombre" => $rConsu["nombre"],
					"precio" => $rConsu["precio"],
					"impuesto" => $rConsu["impuesto"],
					"descuento" => $descuento,
					"cantidad" => $cantidad,
					"descripcion" => $rConsu["descripcion"],
					"imagen" => $rConsu["url_imagen"],
					"precio_calculado" => $precio_calculado
				)
			);
		}
		$conexion->close();
		return $result;
	}

	private static function obtener_descuento($cantidad=0, $idProducto=0)	{
		$conexion = self::iniciar();
		$sql = "SELECT descuento, maximo, minimo FROM productos_descuento WHERE productos_id = $idProducto ";
		$consu = $conexion->query($sql);
		$descuento = 0.00;
		if ($consu->num_rows>0) {
			while ($rConsu = $consu->fetch_assoc()) {
				$minimo = $rConsu["minimo"];
				$maximo = $rConsu["maximo"];
	
				if ($cantidad>=$minimo && $cantidad<$maximo) {
					$descuento = $rConsu["descuento"];					
				}else if($cantidad == $maximo){
					$descuento = $rConsu["descuento"];
				}
			}
		}
		$conexion->close();
		return $descuento;
	}

	public static function calcular_precio($cantidad, $precio, $descuento, $impuesto)	{
		$precioUnitario = $precio*$cantidad;#380000
		$precioDescuento  = ($precioUnitario*$descuento)/100;#0
		$precioDescuento = $precioUnitario-$precioDescuento;#380000
		$precioImpuesto = ($precioDescuento*$impuesto)/100;#72200
		$precioImpuesto = $precioImpuesto+$precioDescuento;#452200
		$precioTotal = $precioImpuesto;

		return $precioTotal;
	}

	public static function info_cot_actualizada($value='')	{
		$html = '';
		$totalCompra = 0;
		if (!empty($_SESSION["CARRITO"])) {
			for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) { 
				
				$html .= '<a class="dropdown-item" href="#">
                           <img src="uploads/'.$_SESSION["CARRITO"][$i]["imagen"].'" alt="" style="width:50px;"> 
                            <b>'.$_SESSION["CARRITO"][$i]["cantidad"].'</b> M<sup>2</sup> <b>'.$_SESSION["CARRITO"][$i]["nombre"].'</b> : $'.number_format($_SESSION["CARRITO"][$i]["precio_calculado"],2,".",",").'
                        </a>';
                $totalCompra += $_SESSION["CARRITO"][$i]["precio_calculado"];
			}
			$html .= '<hr><a class="dropdown-item" href="resumen">
	                   <div class="row">
	                   		<div class="col-lg-6">
	                   			<i class="fa fa-shopping-cart"></i> Ir al resumen 
	                   		</div>	
	                   		<div class="col-lg-6">
	                   			<b>$'.number_format($totalCompra,2,'.',',').'</b>
	                   		</div>
	                   </div>
	                </a>';
		}

		return $html;
	}

	public static function procesar_eliminacion($idProducto='')	{
		if (!empty($_SESSION["CARRITO"])) {
			for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) {
				if (($clave = array_search($idProducto,  $_SESSION["CARRITO"][$i])) !== false && $clave==="id_producto") {
					
				    unset($_SESSION["CARRITO"][$i]);
				}
			}
		}
	}

	public static function procesar_vaciar_carrito()	{
		unset($_SESSION["CARRITO"]);
	}

	public static function cargar_medios_de_pago(){
		$html = '';

		# se cargan los botones electrónicos si existen (PENDIENTE)
		$htmlPendiente = self::cargar_botones_pago();

		# contenido html para el contenedor
		$html .= '
		<div class="card shadow mb-4 text-center align-self-center">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<p> Medios de pago </p>
				<div class="pull-right" id="direEdit">'.((!empty($_SESSION["DATOS_FACTURACION"]))?'<button class="btn btn-warning btn-xs" onclick="procesoPagos.editDirSel();"><i class="fa fa-edit"></i> Seleccionar otra dirección</button>':'').'</div>
			</div>
			<div class="card-body">				
		';
		$contenido = '<div class="card shadow mb-4">';
		
		# se carga el botón de DB si existe
		$pasarela = self::consultaSystem("id", "102");//Activación pagos por Deposito Bancario
		if ($pasarela["estado"]) {
			$html .= '<div class="card-body">
							<button type="button" class="btn btn-success btn-lg" name="metodo_pago" id="bancoBtn" ><i class="fa fa-bank fa-lg"></i> Banco </button>
						</div>
			';
			$html .= self::cargarBancos();
		}
		$contenido .= '</div>';
		$html .= '		
			</div>
		</div>';
		return $html;
	}

	private static function cargarBancos(){
		
		$conexion = self::iniciar();
		$sql = "SELECT nombre, tipo, cuenta, qr_img FROM bancos WHERE estado = '1'";
		$consu = $conexion->query($sql);		
		$contenido = $datosBanco = '';
		/////////////////////
		// Info del banco  //
		/////////////////////
		
		$contenido .= '<div class="modal fade bd-example-modal-lg" id="datosBanco" tabindex="-1" role="dialog" aria-labelledby="btnPasarela" aria-hidden="true" > ';
		while ($rConsu = $consu->fetch_assoc()) {
			$nombre = $rConsu["nombre"];
			$tipo = (($rConsu["tipo"]==1)?'Ahorros':(($rConsu["tipo"]==2)?'Corriente':'Inválido'));
			$cuenta = $rConsu["cuenta"];
			$imgAsociada = $rConsu["qr_img"];
			$datosBanco .= '
				<table class="table table-striped ">
					<tr>
						<td> Nombre : '.$nombre.'</td>
						<td> Cuenta : '.$cuenta.'</td>
						<td> Tipo : '.$tipo.'</td>
						<td> <img src="'.URL_ABSOLUTA.'assets/img/qr/'.$imgAsociada.'" alt="" class="img-thumbnail" ></td>
					</tr>
					
				</table>
			';
		}
		$contenido .= '
			<div class="modal-dialog modal-lg" >
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="newProductoMdl">Pago por Deposito Bancario</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="modal-body">
								<div class="row">
									<div class="col-lg-7">
										<legend>Cuentas asociadas</legend>
										<hr>
										'.$datosBanco.'
									</div>
									<div class="col-lg-5">
										<legend>Datos de pago</legend>
										<hr>
										<form class="user was-validated" id="FormRegistroOrdenCompra" autocomplete="off">
											<br>
											<div class="form-group row">
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label>Número de cuenta</label>
													<input class="form-control" type="text" name="numero_cuenta" value="" placeholder="0123456789" required>
												</div>
												<div class="col-sm-6 mb-3 mb-sm-0">
													<label>Fecha de pago</label>
													<input class="form-control" type="date" name="fecha" value="" placeholder="01/01/2022" required>
												</div>
											</div>
											<div class="form-group">
												<label>Soporte de pago</label>
												<input class="form-control" type="text" name="soporte_pago" value="" placeholder="987654321-0" required>
											</div>
											<div class="form-group">
											</div>
											<input type="hidden" name="entrada" value="compraDepoBanc">
											<button type="submit" class="btn btn-primary btn-user btn-block">
												Finalizar Pago
											</button>
											<hr>
										</form>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		';
		
		
		
		$contenido .= '</div>';

		$conexion->close();
		return $contenido;
	}

	private static function cargar_botones_pago(){
		$contenido = '<div class="card shadow mb-4">';
		$pasarela = self::consultaSystem("relacion", "metodo_pago");
		$contenido .= '<div class="card-body">
						<button class="btn btn-success" name="metodo_pago" id="PAYU"><i class="fa fa-bank"></i> PAYU </button>
					</div>';
		$contenido .= '</div>';
		return $contenido;
	}

	private static function consulta_datos_config(){
		$data["simbolo_moneda"] = '$';
		$data["simbolo_cantidad"] = 'Cant';
		$data["cantidad_decimales"] = 0;
		$datos = self::consultaSystem("relacion", "config_general");
		if ($datos["estado"]) {
			for ($i=0; $i <count($datos["datos"]) ; $i++) {
				$id = (int)$datos["datos"][$i]["id"];
				$valor = $datos["datos"][$i]["valor"];
				if($id==10){# moneda
					$data["simbolo_moneda"] = $valor;
				}
				if($id==11){# cantidad
					$data["simbolo_cantidad"] = $valor;
				}
				if($id==101){# cantidad
					$data["cantidad_decimales"] = $valor;
				}
			}
		}
		return $data;
	}
}
 ?>