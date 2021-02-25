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
			$db		= "pnyees";
			$user	= "root";
			$pass	= "";
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
		    print "¡Error en la conexión!: " . $e->getMessage() . "<br/>";
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

	public static function validar_archivo($FILES){
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
		    	'../uploads/%s.%s',
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
}

?>