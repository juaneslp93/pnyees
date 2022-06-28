<?php 
include "../../model/conexion.php";
include "../../model/mdl_validacionesIn.php";

#validar sesion existente;
ValidacionesIn::validar_sesion_existe();
?>