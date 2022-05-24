<?php 
require "../../model/mdl_vistas_admin.php";
#permisos
VistasAdmin::permiso_pagina();
# nabvar
$navbar = VistasAdmin::navBar();
# menu
$menu = VistasAdmin::menu();

?>