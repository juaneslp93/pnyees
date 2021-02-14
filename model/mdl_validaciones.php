<?php 


/**
 * 
 */
class Validaciones 
{
	
	function __construct()
	{
		# code...
	}

	public static function validar_sesion($value='')
	{
		var_dump($_SESSION["SYSTEM"]);
		if (isset($_SESSION["SYSTEM"]["MASTER"])) {
			header("Location: ".$_SESSION["SYSTEM"]["URL"]);
		}else if (isset($_SESSION["SYSTEM"]["ADMIN"])) {
			header("Location: ".$_SESSION["SYSTEM"]["URL"]);
		}else if (isset($_SESSION["SYSTEM"]["USER"])) {
			header("Location: ".$_SESSION["SYSTEM"]["URL"]);
		}else{
			include "view/login.php";
		}
	}
}
?>