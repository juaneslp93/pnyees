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

	public static function procesar_imagen($FILES='')	{
		$FILES['upfile'] = $FILES['imagenProducto'];
		unset($FILES['imagenProducto']);
		$data = explode("//~",self::validar_archivo($FILES));
		$continue = false;
		$url = '';
		$mensaje = $data[0];
		if ($data[1]) {
			$continue = true;
			$url = @$data[1];
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
}
 ?>