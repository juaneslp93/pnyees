<?php 
@session_start();
require "../../model/conexion.php";
require "../../model/mdl_vistas_tienda.php";
$sitio = explode("/", $_SERVER["REDIRECT_URL"]);
$sitio = end($sitio);
$sitio = explode("&", $sitio);
@$pagina = $sitio[1];

if ("tienda-".@$_GET["id"]===$sitio[0]) {
	$_SESSION["TIENDA"]["ID"] = $_GET["id"];
	$_SESSION["TIENDA"]["COMPRADOR"] = Vistas::reconocer_comprador($_GET["id"]);
}else if(!$_SESSION["TIENDA"]){
	header("Location: tienda-".$_GET["id"]."&1");
}

# si no existe carrito y estoy en pagos o resumen me debe redirigir a catalogo
# nabvar
$navbar = Vistas::navBar();
$navbarTienda = Vistas::navBarTienda($_SESSION["TIENDA"]["ID"]);
$resumenCarrito = Vistas::resumenCarrito($_SESSION["TIENDA"]["ID"], @$_GET["id"]);

?>