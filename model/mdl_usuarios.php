<?php 
/**
 * 
 */
class Usuarios extends Conexion
{
	
	function __construct()	{
		# code...
	}

	public static function eliminar_usuario($idUsuario=0){
		$conexion = self::iniciar();
		$sql = "UPDATE usuarios SET estado = '0' WHERE id=$idUsuario ";
		$consu = $conexion->query($sql);
		if ($consu) {
			$result = true;
			$mensaje = "Eliminación exitosa";
		}else{
			$result = false;
			$mensaje = " Se detecto un problema al tratar de eliminar al usuario";
		}
		$conexion->close();
		return array("proceso"=>$result, "mensaje"=>$mensaje);
	}
}
 ?>