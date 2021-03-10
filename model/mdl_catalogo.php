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
		                            		<a href="detalle-'.$idProductoEncrip.'" ><img src="'.$imagen.'" class="img-responsive img-rounded img-fluid" style="max-height: 300px;" alt="">
		                            		</a>
		                            		<h3>'.$rConsu["nombre"].'</h3>
		                            		$'.$precio.'<br>
		                            		<div class="form-group has-feedback">
											  <i class="fa form-control-feedback">M² </i>
											  <input type="number" class="form-control" value="0">
											</div>
		                            		<button type="button" data-control="'.$idProductoEncrip.'" class="agregarCarrito btn btn-info btn-lg"><i class="fa fa-plus"></i> Agregar al carrito</button>
		                            		<a href="detalle-'.self::encriptar($rConsu["id"], "Det1").'" class="btn btn-warning btn-lg"><i class="fa fa-external-link"></i> Ir al etalle</a>
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
		                            		<img src="'.$imagen.'" class="img-responsive img-rounded img-fluid" alt="">
		                            		<h3>'.$rConsu["nombre"].'</h3>
		                            		<h6>'.$rConsu["descripcion"].'</h6>
		                            		$'.$precio.'<br>
		                            		<div class="form-group has-feedback row col-lg-3">
											  <i class="fa form-control-feedback">M² </i>
											  <input type="number" class="form-control" value="0">
											</div>
		                            		<button type="button" data-control="'.$idProductoEncrip.'" class="agregarCarrito btn btn-info btn-lg"><i class="fa fa-plus"></i> Agregar al carrito</button>
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
}
 ?>