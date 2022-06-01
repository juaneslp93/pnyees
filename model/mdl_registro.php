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

	public static function validador_clave_segura($var){

		$letras 		= array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','ñ','o','p','q','r','s','t','u','v','w','y','x','z');
		$letrasM 		= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','Y','X','Z');
		$numeros 		= array('1','2','3','4','5','6','7','8','9','0');
		$especial 		= array('-','*','?','!','@','#','$','/','(',')','{','}','=','.',',',';',':','_');
		$noPermitidas 	= array('&', 'Ç', 'ü', 'é', 'â', 'ä', 'à', 'å', 'ç', 'ê', 'ë', 'è', 'ï', 'î', 'ì', 'Ä', 'Å', 'É', 'æ', 'Æ', 'ô', 'ö', 'ò', 'û', 'ù', 'ÿ', 'Ö', 'Ü', 'ø', '£', 'Ø', '×', 'ƒ', 'á', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'ª', 'º', '¿', '®', '¬', '½', '¼', '¡', '«', '»', '░', '▒', '▓', '│', '┤', 'Á', 'Â', 'À', '©', '╣', '║', '╗', '╝', '¢', '¥', '┐', '└', '┴', '┬', '├', '─', '┼', 'ã', 'Ã', '╚', '╔', '╩', '╦', '╠', '═', '╬', '¤', 'ð', 'Ð', 'Ê', 'Ë', 'È', 'ı', 'Í', 'Î', 'Ï', '┘', '┌', '█', '▄', '¦', 'Ì', '▀', 'Ó', 'ß', 'Ô', 'Ò', 'õ', 'Õ', 'µ', 'þ', 'Þ', 'Ú', 'Û', 'Ù', 'ý', 'Ý', '¯', '´', '≡', '±', '‗', '¾', '¶', '§', '÷', '¸', '°', '¨', '·', '¹', '³', '²', '■', 'nbsp',"'", '"', '`');
		$min_character 	= 8;  

		$flag1 = false;  
		$flag2 = false;
		$flag3 = false;
		$flag4 = false;
		$flag5 = false;
		$flag6 = false;

		for($i=0;$i<strlen($var);$i++){
			if(in_array($var[$i], $letras)){
				$flag1 = true;
				break;
			}
		}  

		for($i=0;$i<strlen($var);$i++){
			if(in_array($var[$i], $letrasM)){
				$flag2 = true;
				break;
			}
		}  

		for($i=0;$i<strlen($var);$i++){
			if(in_array($var[$i], $numeros)){
				$flag3 = true;
				break;
			}
		} 

		for($i=0;$i<strlen($var);$i++){
			if(in_array($var[$i], $especial)){
				$flag4 = true;
				break;
			}
		} 

		if(strlen($var)>=8){
			$flag5 = true;
		}
		
		$restringe = ' No se permiten: ';
		for ($i = 0; $i <strlen($var); $i++) {
			$flag6 = true;
			if(in_array($var[$i], $noPermitidas)){
				$flag6 = false;
				$restringe .= ' '.$var[$i];
				break;
			}
		}

		if($flag1 && $flag2 && $flag3 && $flag4 && $flag5 && $flag6){
			return array(true, '');
		}else if($flag1 && $flag2 && $flag3 && $flag4 && $flag5 && !$flag6){
			return array(false, $restringe);
		}else{
			return array(false, "Debe contener números, minúsculas, mayúsculas y caráceteres");
		}
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