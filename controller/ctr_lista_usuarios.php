<?php 
session_start();
require "../model/conexion.php";
require "../model/ssp.class.pgsql.php";
require '../model/mdl_usuarios.php';

$casos = array(
	"lista_usuarios",
	"eliminar_usuario"
);
// entrada
$caso = '';
if (!empty($_POST)) {
	if (in_array(@$_POST["entrada"], $casos)) {
		$metodo = "post";
		$caso = @$_POST["entrada"];
	}
}else if(!empty($_GET)){
	if (in_array(@$_GET["entrada"], $casos)) {
		$metodo = 'get';
		$caso = @$_GET["entrada"];
	}
}

switch ($caso) {
	case 'lista_usuarios':
		# code...
		$table = "usuarios";
		$primaryKey = "id";
		$columns  = array(
			array('db' => 'usuario', 'dt'=>0),
			array('db' => 'correo', 'dt'=>1),
			array('db' => 'telefono', 'dt'=>2),
			array('db' => 'estado', 'dt'=>3, 'formatter'=>function($val, $row){
				return (($val)?'<span class="btn btn-success" pull-right> Activo </span>':'<span class="btn btn-danger" pull-right> Inactivo </span>');
			}),
			array('db' => 'id', 'dt'=>4, 'formatter'=>function($val, $row){
				return '
					<div class="dropdown mb-4">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            Opciones
                        </button>
                        <div class="dropdown-menu animated--fade-in"
                            aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="javascript:">Ver detalles</a>
                            <a class="dropdown-item" href="javascript:">Editar</a>
                            <hr>
                            <a class="dropdown-item eliminar_usuario" href="javascript:" data-control="'.$val.'">Eliminar</a>
                        </div>
                    </div>
				';
			}),

		);
		$conexion = Conexion::iniciar();
		$sql_details = Conexion::dataTable(KEYGEN_DATATBLE);
		$data = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, '', '' );
		$conexion->close();
		break;
	case 'eliminar_usuario':
		$pemitido = array("MASTER","ADMIN");
		$continue = true;
		if (in_array($_SESSION["SYSTEM"]["TIPO"], $pemitido)) {
			$id = $_POST["id"];
			$datos = Usuarios::eliminar_usuario($id);
			$continue = $datos["proceso"];
			$mensaje = $datos["mensaje"];
		}else{
			$continue = false;
			$mensaje = "No tiene permisos para realizar esta acción";
		}
		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');

		break;
	
	default:
		# code...
		break;
}

$mostrar = 'NADA';
if ($metodo==="post") {
	$mostrar = $result;
}else if($metodo==="get"){
	$mostrar = $data;
}
echo json_encode($mostrar);

?>