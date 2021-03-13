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
			while ($rConsu = $consu->fetch_assoc()) {
				$precio = ($rConsu["precio"]*$rConsu["impuesto"])/100;
				$precio = $precio+$rConsu["precio"];
				$idProductoEncrip = self::encriptar($rConsu["id"], "Det1");
				$idProductoEncrip = self::formato_encript($idProductoEncrip, "con");
				$ruta = "uploads/";
				$imagen = $ruta.((!empty($rConsu["url_imagen"]))?$rConsu["url_imagen"]:'default.png');
				$cont .= '<div class="col-lg-4 col-md-6 col-sm-12">
		                    <div class="card shadow mb-4">
		                        <div class="card-body">
		                            <div class="row ">
		                            	<div class="col-lg-12">
		                            		<form method="post" name="FormAgregarCarrito" class="agregarCarrito" accept-charset="utf-8">
			                            		<a href="detalle-'.$idProductoEncrip.'" ><img src="'.$imagen.'" class="img-responsive img-rounded img-fluid" style="max-height: 300px;" alt="">
			                            		</a>
			                            		<h3>'.$rConsu["nombre"].'</h3>
			                            		$'.$precio.'<br>
			                            		<div class="form-group has-feedback">
												  <i class="fa form-control-feedback">M² </i>
												  <input type="number" name="cantidad" class="form-control" value="0">
												  <input type="hidden" name="data-control" value="'.$idProductoEncrip.'" >
												  <input type="hidden" name="entrada" value="agregarProducto" >
												</div>
			                            		<button type="submit" class="btn btn-info "><i class="fa fa-plus"></i> Agregar al carrito</button>
			                            		<a href="detalle-'.self::encriptar($rConsu["id"], "Det1").'" class="btn btn-warning "><i class="fa fa-external-link"></i> Ir al etalle</a>
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
			while ($rConsu = $consu->fetch_assoc()) {
				$precio = ($rConsu["precio"]*$rConsu["impuesto"])/100;
				$precio = $precio+$rConsu["precio"];
				$ruta = "uploads/";
				$imagen = $ruta.((!empty($rConsu["url_imagen"]))?$rConsu["url_imagen"]:'default.png');
				$cont .= '<div class="col-lg-12 col-md-12 col-sm-12">
		                    <div class="card shadow mb-12">
		                        <div class="card-body">
		                            <div class="row ">
		                            	<div class="col-lg-12">
		                            		<form method="post" name="FormAgregarCarrito" class="agregarCarrito" accept-charset="utf-8">
			                            		<img src="'.$imagen.'" class="img-responsive img-rounded img-fluid" alt="">
			                            		<h3>'.$rConsu["nombre"].'</h3>
			                            		<h6>'.$rConsu["descripcion"].'</h6>
			                            		$'.$precio.'<br>
			                            		<div class="form-group has-feedback row col-lg-3">
												  <i class="fa form-control-feedback">M² </i>
												  <input type="number" name="cantidad" class="form-control" value="0">
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

	public static function agregar_producto($cantidad='', $idProductoEncrip='')	{
		$idProducto = self::formato_encript($idProductoEncrip, "des");
		$idProducto = self::desencriptar($idProducto, "Det1");
		$result = array("result"=>false, "mensaje"=>"Error al cargar los datos");
		$existe = false;
	
		for ($i=0; $i <count($_SESSION["CARRITO"]) ; $i++) { 
			if ($_SESSION["CARRITO"][$i]["id_producto"] === $idProductoEncrip) {
				$_SESSION["CARRITO"][$i]["cantidad"] += $cantidad;
				$existe = true;
				$result = array("result"=>true, "mensaje"=>"producto actualizado");
				break;
			}
		}
		
		if (!$existe) {
			$conexion = self::iniciar();
			$sql = "SELECT nombre, descripcion, precio, impuesto, url_imagen FROM productos WHERE id= $idProducto AND estado='1'";
			$consu = $conexion->query($sql);
			if (!isset($_SESSION["CARRITO"])) {
				$_SESSION["CARRITO"] = array();
			}
			if ($consu->num_rows>0) {
				$rConsu = $consu->fetch_assoc();
				$descuento = self::obtener_decuento($cantidad, $idProducto);
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
		}
		return $result;
	}

	public static function obtener_decuento($cantidad=0, $idProducto=0)	{
		$conexion = self::iniciar();
		$sql = "SELECT descuento, maximo, minimo FROM productos_descuento WHERE id_producto = $idProducto ";
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
		$precioImpuesto = ($precio*$impuesto)/100;
		$precioImpuesto = $precioImpuesto+$precio;
		$precioImpuesto = $precioImpuesto*$cantidad;

		$precioDescuento  = ($precioImpuesto*$descuento)/100;
		$precioDescuento = $precioImpuesto-$precioDescuento;

		$precioTotal = $precioDescuento;

		return $precioTotal;
	}
}
 ?>