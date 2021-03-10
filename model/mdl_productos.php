<?php 
/**
 * 
 */
class Productos extends Conexion
{
	
	function __construct()	{
		# code...
	}

	public static function eliminar_producto($idProducto=0){
		$conexion = self::iniciar();
		$sql = "UPDATE productos SET estado = '0' WHERE id=$idProducto ";
		$consu = $conexion->query($sql);
		if ($consu) {
			$result = true;
			$mensaje = "Eliminación exitosa";
		}else{
			$result = false;
			$mensaje = " Se detecto un problema al tratar de eliminar el producto";
		}
		$conexion->close();
		return array("proceso"=>$result, "mensaje"=>$mensaje);
	}

	public static function editar_producto($idProducto=0, $campo='', $valor=''){
		$conexion = self::iniciar();
		$sql = "UPDATE productos SET $campo = '$valor' WHERE id=$idProducto ";
		$consu = $conexion->query($sql);
		if ($consu) {
			$result = true;
			$mensaje = "$campo editado";
		}else{
			$result = false;
			$mensaje = " Se detecto un problema al tratar de editar el producto";
		}
		$conexion->close();
		return array("proceso"=>$result, "mensaje"=>$mensaje);
	}

	public static function procesar_imagen($FILES='', $nombreFile='imagenProducto')	{
		$FILES['upfile'] = $FILES[''.$nombreFile];
		unset($FILES[''.$nombreFile]);
		$po = self::validar_archivo($FILES);
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

	public static function registrar_producto($nombre, $precio, $impuesto, $descripcion, $url){
		$conexion = self::iniciar();
		$sql = "INSERT INTO productos (nombre, descripcion, precio, impuesto, url_imagen, estado, fecha_registro) VALUES (?,?,?,?,?,?,?)";
		$sentencia = $conexion->prepare($sql);
		$sentencia->bind_param('ssidsss', $nombre, $descripcion, $precio, $impuesto, $url, $estado, $fecha_registro);
		$nombre = $nombre;
		$descripcion = $descripcion;
		$precio = $precio;
		$impuesto = $impuesto;
		$url = $url;
		$estado = '1';
		$fecha_registro = date('Y-m-d H:m:s');

		$result = true;
		$mensaje = '';
		if (!$sentencia->execute()) {
			$result = false;
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Hubo un problema al insertar los datos. Error code ['.$sentencia->errno.']'.$sentencia->error.'</h1></span>';
		}
		$conexion->close();
		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	public static function cargar_descuentos($idProducto='')	{
		$conexion = Conexion::iniciar();
		$sql = "SELECT maximo, minimo, descuento FROM productos_descuento WHERE id_producto= $idProducto ";
		$consu = $conexion->query($sql);
		$x = 0;
		$html = '';
		if ($consu->num_rows>0) {
			while ($rConsu = $consu->fetch_assoc()) {
				$maximo = $rConsu["maximo"];
				$minimo = $rConsu["minimo"];
				$descuento = $rConsu["descuento"];

				$html .= '<div id="'.$x.'" class="row col-lg-12">';
				$html .= '<div class="col-sm-3 mb-3 mb-sm-0">
                        <input type="number" class="form-control form-control-user" name="min[]" placeholder="Mínimo" value="'.$minimo.'">
	                    </div>
	                    <div class="col-sm-3 mb-3 mb-sm-0">
	                        <input type="number" class="form-control form-control-user" name="max[]" placeholder="Máximo" value="'.$maximo.'">
	                    </div>
	                    <div class="col-sm-3 mb-3 mb-sm-0">
	                        <input type="number" step="any" class="form-control form-control-user" name="descuento[]" placeholder="Descuento" value="'.$descuento.'">
	                    </div>
	                    <div class="col-sm-3 mb-3 mb-sm-0">
	                        <a class="eliminarItem btn btn-danger" data-control="'.$x.'"><i class="fa fa-trash"></i></a>
	                    </div>';
        		$html .= '</div>';
                $x++;
			}
		}
		$conexion->close();

		return $html;
	}

	public static function registrar_descuentos($idProducto=0, $min=0, $max=0, $descuento=0){
		$conexion = Conexion::iniciar();
		$sql = "INSERT INTO productos_descuento (id_producto, minimo, maximo, descuento) VALUES (?,?,?,?)";
		$sentencia = $conexion->prepare($sql);
		$sentencia->bind_param("iiid", $idProducto, $min, $max, $descuento);
		$idProducto = $idProducto;
		$min = $min;
		$max = $max;
		$descuento = $descuento;

		$result = true;
		$mensaje = '';
		if (!$sentencia->execute()) {
			$result = false;
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Hubo un problema al insertar los datos. Error code ['.$sentencia->errno.']'.$sentencia->error.'</h1></span>';
		}
		$conexion->close();
		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	public static function eliminar_descuentos($idProducto=0){
		$conexion = Conexion::iniciar();
		$sql = "DELETE FROM productos_descuento WHERE id_producto= $idProducto ";
		$conexion->query($sql);
		$conexion->close();
	}
}
 ?>