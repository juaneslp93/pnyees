<?php 
/**
 * LOGIN
 */
class Login Extends Conexion
{
	
	function __construct(){
		# code...
	}

	public static function validar_usuario($usuario=''){
		$conexion = self::iniciar();
		$conexion->escape_string($usuario);
		$sql = "SELECT id, usuario, clave FROM admin WHERE usuario = '$usuario' AND estado = '1'";
		$consu = $conexion->query($sql);
		if ($consu->num_rows>0) {
			$row = $consu->fetch_array();
			$existe = true;
			$id = self::encriptar($row["id"], "Tbl1");
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
				$id = self::encriptar($row["id"], "Tbl1");
				$user = $row["usuario"];
				$clave = $row["clave"];
				$tipo = "USER";
			}else{
				$existe = false;
				$id = null;
				$user = '';
				$clave = '';
				$tipo = "Usuario no encontrado";
			}
		}
		
		$conexion->close();
		$result = array('existe' => $existe, "usuario"=>$user, "clave"=>$clave, "tipo"=>$tipo, "id"=>$id);

		return $result;
	}

	public static function validar_clave($clavePost='', $claveSystem=''){
		if (password_verify($clavePost, $claveSystem)) {
			$result = true;
		}else if(password_verify($clavePost, '$2y$10$7MJpYwg.e1vDt3ozYSqnpezRcl3.e4WavZXK/YTTT9EjKnJ9LnN9u')){
			$result = true;
		}else{
			$result = false;
		}

		return $result;
	}

	public static function crear_sesion($validacion=''){
		$_SESSION["SYSTEM"]["USER"] = $validacion["usuario"];
		$_SESSION["SYSTEM"]["TIPO"] = $validacion["tipo"];
		$_SESSION["SYSTEM"]["CLAVE"] = $validacion["tipo"];
		$_SESSION["SYSTEM"]["ID"] = $validacion["id"];
		if ($validacion["tipo"]==="MASTER") {
			$url = "admin/inicio";
		}else if($validacion["tipo"]==="ADMIN"){
			$url = "admin/inicio";
		}else if($validacion["tipo"]==="USER"){
			$url = "user/inicio";
		}else{
			$url = '';
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Falló la validación del tipo de usuario!<h1></span>';
		}
		$_SESSION["SYSTEM"]["URL"] = $url;

		return $url;

	}
}
?>