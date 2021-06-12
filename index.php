<?php 
session_start();

$_SESSION["LICENCIA"] = array("estado"=>true, "key"=>'');
echo password_hash(12345, PASSWORD_DEFAULT);
require "controller/ctr_validaciones.php";

?>