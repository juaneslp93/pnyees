<?php 
@session_start();
require "../../model/mdl_vistas_admin.php";

# nabvar
$navbar = VistasAdmin::navBar();
# menu
$menu = VistasAdmin::menu();

?>