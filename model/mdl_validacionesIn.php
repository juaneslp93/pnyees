<?php 

/**
 * 
 */
class ValidacionesIn
{
	
	function __construct()
	{
		# code...
	}

	public static function validar_sesion_existe($value='')
	{

		if (isset($_SESSION["SYSTEM"])) {
			$carpeta1 = explode("/", $_SERVER["REDIRECT_URL"]);
			$carpetaActual = $carpeta1[2];
			$carpeta2 = explode("/", $_SESSION["SYSTEM"]["URL"]);
			$carpetaElegida =  $carpeta2[0];
			
			if ($carpetaActual !== $carpetaElegida) {
				$url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/'.$carpeta1[1].'/'.$_SESSION["SYSTEM"]["URL"];
				header("Location: $url ");
			}
		}else{
			$url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"];
			header("Location: $url ");
		}
	}
}
?>