<?php 
@session_start();
require "../../model/conexion.php";
require "../../model/mdl_vistas_tienda.php";

if ("tienda-".$_GET["id"]===$_SERVER["REDIRECT_URL"]) {
	$_SESSION["TIENDA"]["ID"] = $_GET["id"];
	$_SESSION["TIENDA"]["COMPRADOR"] = Vistas::reconocer_comprador($_GET["id"]);
}else if(!$_SESSION["TIENDA"]){
	header("Location: tienda-".$_GET["id"]);
}
# nabvar
$navbar = Vistas::navBar();
$navbarTienda = Vistas::navBarTienda($_SESSION["TIENDA"]["ID"]);

?>