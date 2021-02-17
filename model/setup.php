<?php  

# mostrar errores en pantalla
ini_set("display_errors", 1);

define("KEYGEN_DATATBLE", random());



/* ---------------------------------------------------------------------------------- */
function random()	{		 
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.,-';
	return substr(str_shuffle($permitted_chars), 0, 16);
}
?>