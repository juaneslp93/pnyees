<?php  
require "setup.php";

/**
 * 
 */
class Conexion
{
	
	function __construct()
	{
		# code...
	}

	public static function iniciar(){
		// $conexion = new mysqli($server, $user, $pass, $db);
		try {
			$server = "localhost";
			$db		= "pnyees";//id17442900_pnyeesdb
			$user	= "root";//pnyeeuser
			$pass	= "";//gl4z6vn$\h7&Oi%F
			$key 	= KEYGEN_DATATBLE;
			$conexion = new mysqli($server, $user, $pass, $db);	

			
			//////////////////////////////////////////////////////////////////
			// master_passoword: 	   Pyn335_2021                          //
			// $2y$10$7MJpYwg.e1vDt3ozYSqnpezRcl3.e4WavZXK/YTTT9EjKnJ9LnN9u //
			//////////////////////////////////////////////////////////////////
			
		    
			$_SESSION["CONEXIONDATATABLE"] = serialize(array(
				self::encriptar($server.'.-n',$key), 
				self::encriptar($db.'.-n',$key), 
				self::encriptar($user.'.-n',$key), 
				self::encriptar($pass.'.-n',$key)
			));
			return $conexion;
		} catch (PDOException $e) {
		    echo  "¡Error en la conexión!: " . $e->getMessage() . "<br/>";
		    die();
		}

		return $conexion;
	}

	public static function encriptar($cadena='', $key=''){
		$result = '';
		for ($i=0; $i <strlen($cadena) ; $i++) { 
			$char = substr($cadena, $i, 1);
			@$keyChar= substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keyChar));
			$result .= $char;
		}

		return base64_encode($result);
	}

	public static function desencriptar($cadena='', $key=''){
		$result = '';
		$cadena = base64_decode($cadena);
		for ($i=0; $i <strlen($cadena) ; $i++) { 
			$char = substr($cadena, $i, 1);
			@$keyChar= substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keyChar));
			$result .= $char;
		}

		return $result;
	}

	public static function dataTable($key='')	{
		$datosConexion = unserialize($_SESSION["CONEXIONDATATABLE"]);
		return array(
			'user'=>str_replace(".-n", '', self::desencriptar($datosConexion[2], $key)),
			'pass'=>str_replace(".-n", '', self::desencriptar($datosConexion[3], $key)),
			'db'=>str_replace(".-n", '', self::desencriptar($datosConexion[1], $key)),
			'host'=>str_replace(".-n", '', self::desencriptar($datosConexion[0], $key))
		);
	}

	public static function formato_nro_compra($nro_compra='')	{
		$number = $nro_compra;
		$length = 10;
		$string = substr(str_repeat(0, $length).$number, - $length);
		return $string;
	}

	private static function encrypt_decrypt($action, $string) {
	     /* =================================================
	      * ENCRYPTION-DECRYPTION
	      * =================================================
	      * ENCRYPTION: encrypt_decrypt('encrypt', $string);
	      * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
	      */
	     $output = false;
	     $encrypt_method = "AES-256-CBC";
	     $secret_key = 'PNYEES-SERVICE-KEY';
	     $secret_iv = 'PNYEES-SERVICE-VALUE';
	     // hash
	     $key = hash('sha256', $secret_key);
	     // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	     $iv = substr(hash('sha256', $secret_iv), 0, 16);
	     if ($action == 'encrypt') {
	         $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
	     } else {
	         if ($action == 'decrypt') {
	             $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	         }
	     }
	     return $output;
	 }

    public static function encriptTable($cadena){
		$encrypt = self::encrypt_decrypt('encrypt', $cadena);
		$encrypt_compuesto = str_replace(array('/','+'), array('_','-'), $encrypt);
		$encrypt_compuesto = substr($encrypt_compuesto, 0, -1);
		return $encrypt_compuesto;
	}

	public static function decriptTable($cadena){
		$var_original = str_replace(array('_','-'), array('/','+'), $cadena);
		$desencriptar = self::encrypt_decrypt('decrypt', $var_original);
		return $desencriptar;
	}

	public static function validar_archivo($FILES, $folder="uploads"){
		header('Content-Type: text/plain; charset=utf-8');

		try {
		   
		    // Undefined | Multiple Files | $FILES Corruption Attack
		    // If this request falls under any of them, treat it invalid.
		    if (
		        !isset($FILES['upfile']['error']) ||
		        is_array($FILES['upfile']['error'])
		    ) {
		        throw new RuntimeException('Invalid parameters.');
		    }

		    // Check $FILES['upfile']['error'] value.
		    switch ($FILES['upfile']['error']) {
		        case UPLOAD_ERR_OK:
		            break;
		        case UPLOAD_ERR_NO_FILE:
		            throw new RuntimeException('El archivo no fue cargado.');
		        case UPLOAD_ERR_INI_SIZE:
		        case UPLOAD_ERR_FORM_SIZE:
		            throw new RuntimeException('Límite de carga ha sido excedido.');
		        default:
		            throw new RuntimeException('Error desconocido.');
		    }

		    // You should also check filesize here.
		    if ($FILES['upfile']['size'] > 1000000) {
		        throw new RuntimeException('El tamaño del archivo no es permitido.');
		    }

		    // DO NOT TRUST $FILES['upfile']['mime'] VALUE !!
		    // Check MIME Type by yourself.
		    $finfo = new finfo(FILEINFO_MIME_TYPE);
		    if (false === $ext = array_search(
		        $finfo->file($FILES['upfile']['tmp_name']),
		        array(
		            'jpg' => 'image/jpeg',
		            'png' => 'image/png',
		            'gif' => 'image/gif',
		        ),
		        true
		    )) {
		        throw new RuntimeException('Formato inválido.');
		    }

		    // You should name it uniquely.
		    // DO NOT USE $FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
		    // On this example, obtain safe unique name from its binary data.
		    $destino = sprintf(
		    	"../$folder/%s.%s",
		        sha1_file($FILES['upfile']['tmp_name']),
		        $ext
		        );
		    if (!move_uploaded_file($FILES['upfile']['tmp_name'], $destino)) {
		        throw new RuntimeException('Falla al mover el archivo.');
		    }

		    return 'El archivo fue cargado exitosamente. //~'.$destino;

		} catch (RuntimeException $e) {

		    return $e->getMessage();
		}
	}

	public static function formato_encript($variable='', $preg='con'){
		if ($preg=="des") {
			return preg_replace('~-~', '=', $variable);
		}else if($preg=="con"){
			return preg_replace('~=~', '-', $variable);
		}
	}

	public static function consultaSystem($campo='', $valCampo=''){
		$conexion = self::iniciar();
		$sql = "SELECT id, nombre, valor, defecto, estado FROM sistema WHERE $campo='$valCampo'";
		$sentencia = $conexion->prepare($sql);
		
		$mensaje = '';
		$nombre = $valor = $defecto = $estado = null;
		$datos = array();
		if (!$sentencia->execute()) {
			$result = false;
			$mensaje = '<span class="text text-danger"><h1 class="h4 text-gray-900 mb-4">¡Hubo un problema al consultar datos del sistema!. Error code ['.$sentencia->errno.']'.$sentencia->error.'</h1></span>';
		}else{
			$result = true;
			$sentencia->store_result();
			$sentencia->bind_result($id, $nombre, $valor, $defecto, $estado);
			if ($sentencia->num_rows==1) {
				$sentencia->fetch();
				$result = $estado;
				$datos = array("id"=>$id, "nombre"=>$nombre, "valor"=>$valor, "defecto"=>$defecto, "estado"=>$estado);
			}else{
				while($sentencia->fetch()){
					array_push($datos, array("id"=>$id, "nombre"=>$nombre, "valor"=>$valor, "defecto"=>$defecto, "estado"=>$estado));
				}				
			}
		}

		$conexion->close();
		return array("estado"=>$result, "mensaje"=>$mensaje, "datos"=>$datos);
	}

	public static function editSystem($campo='', $valor='', $campoWhere='', $valorWhere='')	{
		$conexion = self::iniciar();
		$where = '';
		if ($campoWhere!='' && !empty($campoWhere)) {
			$where = "WHERE $campoWhere = '$valorWhere'";
		}
		$sql = "UPDATE sistema SET $campo = '$valor' $where ";
		$sentencia = $conexion->prepare($sql);

		$mensaje = '';
		$result = true;
		if (!$sentencia->execute()) {
			$result = false;
			$mensaje = $sentencia->error.' ['.$sentencia->errono.'] ';
		}
		$conexion->close();

		return array("estado"=>$result, "mensaje"=>$mensaje);
	}

	public static function cargar_departamentos($idDepartamento=0){
		$conexion = self::iniciar();		
		$sql = "SELECT id, nombre, codigo FROM departamentos ORDER BY nombre ASC";
		$consu = $conexion->query($sql);
		$opciones = '';
		if ($consu->num_rows>0) {
			while ($rConsu = $consu->fetch_assoc()) {
				$opciones .= '<option value="'.$rConsu["codigo"].'" '.(($idDepartamento==$rConsu["id"])?'selected':'').'>'.$rConsu["nombre"].'</option>';
			}
		}
		$html = '
			<div class="form-group">
				<label class="control-label" >Departamento</label>
				<select name="departamento" id="departamento" class="form-control" required>
					<option value="">**SELECCIONE**</option>
					'.$opciones.'
				</select>
			</div>
			<div class="form-group" id="cargarMunicipios">
				<label class="control-label" >Municipio</label>
				<select name="municipio" id="municipio" class="form-control">
					<option value="">**SELECCIONE**</option>
				</select>
			</div>
			<script type="text/javascript" charset="utf-8" async defer>
				$("#departamento").on("change", function(){
					var codigo = $(this).val();
					$.ajax({
						url: "controller/ctr_pagos.php",
						type: "POST",
						dataType: "json",
						data:{"entrada":"cargarMunicipios", "codigo":codigo},
					})
					.done(function(result) {
						$("#cargarMunicipios").html(result.html);
					})
					.fail(function() {
						console.log("error");
					});
				});
			</script>
		';
		$conexion->close();

		return $html;
	}

	public static function cargar_municipios($idMunicipio=0, $codigoDepartamento=0){
		$conexion = self::iniciar();
		$where = '';
		if ($codigoDepartamento>0) {
			$where = " WHERE codigo_departamento = $codigoDepartamento ";
		}		
		$sql = "SELECT id, nombre_municipio, codigo_departamento, codigo_municipio FROM municipios $where ORDER BY nombre_municipio ASC";
		$consu = $conexion->query($sql);
		$opciones = '';
		if ($consu->num_rows>0) {
			while ($rConsu = $consu->fetch_assoc()) {
				$opciones .= '<option value="'.$rConsu["codigo_municipio"].'" '.(($idMunicipio==$rConsu["id"])?'selected':'').'>'.$rConsu["nombre_municipio"].'</option>';
			}
		}
		$html = '
			<div class="form-group">
				<label class="control-label" >Municipio</label>
				<select name="municipio" id="municipio" class="form-control" required>
					<option value="">**SELECCIONE**</option>
					'.$opciones.'
				</select>
			</div>
		';
		$conexion->close();

		return $html;
	}

	public static function fecha_sistema(){
		return date('Y-m-d H:i:s');
	}

	public static function generar_numero_orden(){
		return date("YmdHis").random(2);
	}

	public static function formato_decimal($valor=0){
		$decimal = self::consultaSystem("id",101)["datos"]["valor"];
		if($decimal>0){
			return number_format($valor,$decimal,',','.');
		}else{
			return number_format($valor,0,',','.');
		}
	}

	public static function formato_nro_factura($valor=0, $tam=10){
		return str_pad($valor, $tam, "0", STR_PAD_LEFT);  // produce "00001"
	}

	public static function saber_permiso_asociado($idPermiso=0){
		$permisos = array("ver"=>null, "crear"=>null, "editar"=>null, "eliminar"=>null, "modulo"=>null);
		for ($i=0; $i <count($_SESSION["SYSTEM"]["PERMISOS"]) ; $i++) {
			$idPermisoCargado = (int)$_SESSION["SYSTEM"]["PERMISOS"][$i]["id_modulo"];
			if($idPermisoCargado==$idPermiso){# modulo
				$ver = $_SESSION["SYSTEM"]["PERMISOS"][$i]["ver"];
				$crear = $_SESSION["SYSTEM"]["PERMISOS"][$i]["crear"];
				$editar = $_SESSION["SYSTEM"]["PERMISOS"][$i]["editar"];
				$eliminar = $_SESSION["SYSTEM"]["PERMISOS"][$i]["eliminar"];
				$modulo = $_SESSION["SYSTEM"]["PERMISOS"][$i]["modulo"];
				$permisos = array("ver"=>$ver, "crear"=>$crear, "editar"=>$editar, "eliminar"=>$eliminar, "modulo"=>$modulo);
				break;
			}
		}
		return $permisos;
	}

	protected static function enviar_correo($html = '', $para = array(), $titulo='', $cc='', $bcc=''){
		/**
		 * $para puede ser con varios destinatarios separados por ","
		 * $titulo es el "asunto" del correo
		 * $html el es texto en html
		 * $cabeceras el tipo de contenido y encabezados como tal.
		 */
		$pasarela = Conexion::consultaSystem("relacion", "config_facturacion");		
		if ($pasarela["estado"]) {
			for ($i=0; $i <count($pasarela["datos"]) ; $i++) { 
				$id = $pasarela["datos"][$i]["id"];
				$valor = $pasarela["datos"][$i]["valor"];
				if($id==1){
					$tituloEmpresa =  $valor;
				}else if($id==2){
					$nitEmpresa =  $valor;
				}else if($id==3){
					$contactoEmpresa = $valor;
				}else if($id==4){
					$direcionEmpresa = $valor;
				}else if($id==5){
					$correoEmpresa = $valor;
				}
			}
            }
		// mensaje
		$mensaje = '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<!-- Custom fonts for this template-->
				'.Scripts::headers(array("fontAwesome","fonts.googleapis","sb-admin-2")).'
			</head>
			<body class="bg-gradient-default">
				<div class="container">
					<!-- Outer Row -->
					<div class="row justify-content-center">
						<div class="col-xl-10 col-lg-12 col-md-9">
							<div class="card o-hidden border-0 shadow-lg my-5">
								<div class="card-body p-0">
									<!-- Nested Row within Card Body -->
									<div class="row">
										<div class="col-lg-12 col-md-12">
											'.$html.'
										</div>
										<hr>
										<div class="col-lg-12 col-md-12">
											<p class="col-lg-12">
												'.$tituloEmpresa.'<br>
												Nit: '.$nitEmpresa.'<br>
												Teléfono: '.$contactoEmpresa.'<br>
												Correo: '.$correoEmpresa.'<br>
												Dirección: '.$direcionEmpresa.'<br>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				'.Scripts::footers(array("jquery","bootstrap","sb-admin-2", "forgot")).' 
			</body>
			</html>
		';

		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Cabeceras adicionales
		$to = 'To: ';
		$cont = 0;
		$cantDest = count($para);
		for ($i=0; $i <$cantDest ; $i++) { 
			$cont++;
			$to .= '<'.$para[$i].(($cont<$cantDest)?', ':'').'>';
		}
		$cabeceras .= $to . "\r\n";
		$cabeceras .= "From: $tituloEmpresa <$correoEmpresa>" . "\r\n";
		$cabeceras .= ((!empty($cc))?"Cc: $cc" . "\r\n":'');
		$cabeceras .= ((!empty($cc))?"Bcc: $bcc" . "\r\n":'');

		// Enviarlo
		$para = implode(', ',$para);
		// echo $mensaje;
		if(@mail($para, $titulo, $mensaje, $cabeceras)){
			return true;
		}else{
			return false;
		}
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
			return array(false, "Debe contener números, minúsculas, mayúsculas y caractéres");
		}
	}
}

?>