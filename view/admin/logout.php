<?php  
require '../../model/conexion.php';
session_start();
session_destroy();
header("Location: ".URL_ABSOLUTA);
exit;
?>