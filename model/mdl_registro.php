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
		$sql = "INSERT INTO usuarios (usuario, clave, correo, telefono, estado, nombre, apellido) VALUES (?,?,?,?,?,?,?)";
		$sentencia = $conexion->prepare($sql);
		$sentencia->bind_param('sssisss', $usuario, $clave, $correo, $telefono, $estado, $nombre, $apellido);
		$usuario = $usuario;
		$clave = $clave;
		$correo = $correo;
		$telefono = $telefono;
		$estado = '1';
		$nombre = $nombre;
		$apellido = $apellido;

		$result = true;
		$mensaje = '';
		if (!$sentencia->execute()) {
			$restult = false;
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

		$restinge = ' No se permiten: ';
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
}
?>