<?php 
@session_start();
include "../../model/mdl_validacionesIn.php";

#validar sesion existente;
ValidacionesIn::validar_sesion_existe();
?>