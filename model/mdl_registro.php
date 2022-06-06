<?php 
/**
 * LOGIN
 */
class Registro Extends Conexion
{
	
	function __construct(){
		# code...
	}

	public static function registrar_usuario($usuario='', $clave='', $correo='', $telefono=0, $nombre='', $apellido=''){
		$conexion = self::iniciar();
		$sql = "INSERT INTO usuarios (usuario, clave, correo, telefono, estado, nombre, apellido, fecha_registro) VALUES (?,?,?,?,?,?,?,?)";
		$sentencia = $conexion->prepare($sql);
		$sentencia->bind_param('sssissss', $usuario, $clave, $correo, $telefono, $estado, $nombre, $apellido, $fecha_registro);
		$usuario = $usuario;
		$clave = $clave;
		$correo = $correo;
		$telefono = $telefono;
		$estado = '1';
		$nombre = $nombre;
		$apellido = $apellido;
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

	public static function validar_usuario_registro($usuario=''){
		$conexion = self::iniciar();
		$conexion->escape_string($usuario);
		$sql = "SELECT id, usuario, clave FROM admin WHERE usuario = '$usuario' AND estado = '1'";
		$consu = $conexion->query($sql);
		if ($consu->num_rows>0) {
			$row = $consu->fetch_array();
			$existe = true;
			$user = $row["usuario"];
			$clave = $row["clave"];
			if ($row["id"]==1) {
				$tipo = "MASTER";
			}else{
				$tipo = "ADMIN";
			}
		}else{
			$sql = "SELECT id, usuario, clave FROM usuarios WHERE usuario = '$usuario' AND estado = '1'";
			$consu = $conexion->query($sql);
			if ($consu->num_rows>0) {
				$row = $consu->fetch_array();
				$existe = true;
				$user = $row["usuario"];
				$clave = $row["clave"];
				$tipo = "USER";
			}else{
				$existe = false;
				$user = '';
				$clave = '';
				$tipo = "Usuario no encontrado";
			}
		}
		
		$conexion->close();
		$result = array('existe' => $existe, "usuario"=>$user, "clave"=>$clave, "tipo"=>$tipo);

		return $result;
	}

	public static function validar_correo_existe($correo){
		# consultamos en admin
		$continue = true;
		$mensaje = " Por verifique su bandeja de entrada, sino encuentra el mensaje en esta sección verifique en la sección de Spam. ";
		if(self::consultar_correo_admin($correo)){
			$continue = false;
		}
		# consultamos en clientes
		if($continue){
			if(self::consultar_correo_usuario($correo)){
				$continue = false;
			}
		}
		# no existe
		if($continue){
			$continue = false;
			$mensaje = "El correo $correo no existe o no hubo forma de enviar el mail.";
		}
		return array("result"=>$continue, "mensaje"=>$mensaje);
	}

	private static function consultar_correo_admin($correo = null){
		$conexion = self::iniciar();
		$correo = $conexion->real_escape_string($correo);
		$sql ="SELECT id, usuario FROM admin WHERE correo LIKE '$correo' ";
		$consu = $conexion->query($sql);
		$result = false;
		if($consu->num_rows>0){
			$rConsu = $consu->fetch_assoc();
			if($rConsu["id"]!=null){
				$newClave = random(16);
				$clave_hash = password_hash($newClave, PASSWORD_DEFAULT);
				$conexion->query("UPDATE admin SET clave_prov = '$clave_hash' WHERE id={$rConsu["id"]}");
				$user = $rConsu["usuario"];
				$idEncript = self::encriptar($rConsu["id"], "C0rR");
				
				$html = '
					<p class="col-lg-12">
						Hola '.$user.', <br>
						Recibimos una solicitud de regeneración de contraseña por lo que le hemos hecho llegar una contraseña provisional.<br>
						Por seguridad si usted no realizó esta solicitud no tenga en cuenta este correo y contacte con soporte o administración.

						Su clave provicional es: <b>'.$newClave.'</b> <br>

						Si realmente está seguro de regenerar su contraseña por favor de <a href="'.URL_ABSOLUTA.'recuperar'.$idEncript.'"> Clic aquí</a>
					</p>
				';
				$result = self::enviar_correo($html, array($correo), "Recuperación de contraseña");
			}
		}
		$conexion->close();
		return $result;
	}

	private static function consultar_correo_usuario($correo = null){
		$conexion = self::iniciar();
		$correo = $conexion->real_escape_string($correo);
		$sql ="SELECT id, usuario FROM usuarios WHERE correo LIKE '$correo' ";
		$consu = $conexion->query($sql);
		$result = false;
		if($consu->num_rows>0){
			$rConsu = $consu->fetch_assoc();
			if($rConsu["id"]!=null){
				$newClave = random(16);
				$clave_hash = password_hash($newClave, PASSWORD_DEFAULT);
				$conexion->query("UPDATE usuarios SET clave_prov = '$clave_hash' WHERE id={$rConsu["id"]}");
				$user = $rConsu["usuario"];
				$idEncript = self::encriptar($rConsu["id"], "C0rR");
				
				$html = '
					<p class="col-lg-12">
						Hola '.$user.', <br>
						Recibimos una solicitud de regeneración de contraseña por lo que le hemos hecho llegar una contraseña provisional.<br>
						Por seguridad, si usted no realizó esta solicitud no tenga en cuenta este correo y contacte con soporte o administración.

						Su clave provicional es: <b>'.$newClave.'</b> <br>

						Si realmente está seguro de regenerar su contraseña por favor de <a href="'.URL_ABSOLUTA.'recuperar'.$idEncript.'"> Clic aquí</a>
					</p>
				';
				$result = self::enviar_correo($html, array($correo), "Recuperación de contraseña");
			}
		}
		$conexion->close();
		return $result;
	}

	public static function aprobar_regenerar_contrasena($idUsuario=0){
		$conexion = self::iniciar();
		$sql = "SELECT clave_prov FROM admin WHERE id=$idUsuario ";
		$consu = $conexion->query($sql);
		$rConsu = $consu->fetch_assoc();
		if($rConsu["clave_prov"]!=null){# consultamos si la generación es de admin
			$newClave = $rConsu["clave_prov"];
			$conexion->query("UPDATE admin SET clave_prov=null, clave='$newClave' WHERE id=$idUsuario ");
		}else{
			$sql = "SELECT clave_prov FROM usuarios WHERE id=$idUsuario ";
			$consu = $conexion->query($sql);
			$rConsu = $consu->fetch_assoc();
			if($rConsu["clave_prov"]!=null){# consultamos si la generación es de usuarios
				$newClave = $rConsu["clave_prov"];
				$conexion->query("UPDATE usuarios SET clave_prov=null, clave='$newClave' WHERE id=$idUsuario ");
			}
		}
		$conexion->close();
	}
}
?>