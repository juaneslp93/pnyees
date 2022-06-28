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
		if (@$_SESSION["SYSTEM"]["TIPO"]==="MASTER") {
			header("Location: ".$_SESSION["SYSTEM"]["URL"]);
		}else if (@$_SESSION["SYSTEM"]["TIPO"]==="ADMIN") {
			header("Location: ".$_SESSION["SYSTEM"]["URL"]);
		}else if (@$_SESSION["SYSTEM"]["TIPO"]==="USER") {
			header("Location: ".$_SESSION["SYSTEM"]["URL"]);
		}else{
			include "view/login.php";
		}
	}
}
?>