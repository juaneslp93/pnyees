<?php  

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
		$server = "localhost";
		$db		= "pnyees";
		$user	= "root";
		$pass	= "";
		$conexion = new mysqli($server, $user, $pass, $db);

		return $conexion;
	}
}

require "setup.php";
?>