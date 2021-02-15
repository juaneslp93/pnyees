<?php 
session_start();
require "../../model/mdl_vistas_admin.php";

# nabvar
$navbar = Vistas::navBar();
# menu
$menu = Vistas::menu();

?>