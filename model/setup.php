<?php  

# mostrar errores en pantalla
ini_set("display_errors", 1);

define("KEYGEN_DATATBLE", random(16));

date_default_timezone_set('America/Bogota');



/* ---------------------------------------------------------------------------------- */
function random($cantidad)	{		 
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.,-';
	return substr(str_shuffle($permitted_chars), 0, $cantidad);
}
?>