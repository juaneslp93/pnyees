<?php 
session_start();
require "../../model/mdl_vistas.php";

# nabvar
$navbar = Vistas::navBar();
# menu
$menu = Vistas::menu();

?>