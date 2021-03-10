<?php 
@session_start();
require "../../model/mdl_vistas_user.php";

# nabvar
$navbar = Vistas::navBar();
# menu
$menu = Vistas::menu();

?>