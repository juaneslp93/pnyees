<?php  

# mostrar errores en pantalla
ini_set("display_errors", 1);
define("RAIZ", "/pnyees/");
define("KEYGEN_DATATBLE", random(16));
define("ES_PRODUCCION", false);# definimos si la plataforma está en producción
date_default_timezone_set('America/Bogota');
$rutaSystem = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].RAIZ;
define("URL_ABSOLUTA", $rutaSystem);
/* ---------------------------------------------------------------------------------- */
function random($cantidad, $caracteres=false)	{		 
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.(($caracteres)?'.,-[{()})/&%$#"!¡¿?=':'');
	return substr(str_shuffle($permitted_chars), 0, $cantidad);
}
@session_start();# declaramos las sesiones
?>