<?php 
session_start();

$_SESSION["LICENCIA"] = array("estado"=>true, "cliente");

require "controller/ctr_validaciones.php";

?>