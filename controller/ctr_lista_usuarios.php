<?php 
@session_start();
require "../model/conexion.php";
require "../model/ssp.php";
require '../model/mdl_usuarios.php';

$casos = array(
	"lista_usuarios",
	"eliminar_usuario",
	"editar_usuario"
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
			array('db' => 'usuario', 'dt'=>0, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				$clientes       = Conexion::saber_permiso_asociado(7);# permisos
				if($clientes["editar"]){
					$res = '<div class="usuarioEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
						'.((!empty($val))?$val:'------').'
					</div>
					<div id="U'.$idEncrip.'"></div>';
				}else{
					$res = ((!empty($val))?$val:'------');
				}
				return $res;
			}),
			array('db' => 'nombre', 'dt'=>1, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				$clientes       = Conexion::saber_permiso_asociado(7);# permisos
				if($clientes["editar"]){
					$res = '<div class="nombreEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
						'.((!empty($val))?$val:'------').'
					</div>
					<div id="N'.$idEncrip.'"></div>';
				}else{
					$res = ((!empty($val))?$val:'------');
				}
				return $res;
			}),
			array('db' => 'apellido', 'dt'=>2, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				$clientes       = Conexion::saber_permiso_asociado(7);# permisos
				if($clientes["editar"]){
					$res = '<div class="apellidoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
							'.((!empty($val))?$val:'------').'
					</div>
					<div id="A'.$idEncrip.'"></div>';
				}else{
					$res = ((!empty($val))?$val:'------');
				}
				return $res;
			}),
			array('db' => 'correo', 'dt'=>3, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				$clientes       = Conexion::saber_permiso_asociado(7);# permisos
				if($clientes["editar"]){
					$res = '<div class="correoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
						'.((!empty($val))?$val:'------').'
					</div>
					<div id="C'.$idEncrip.'"></div>';
				}else{
					$res = ((!empty($val))?$val:'------');
				}
				return $res;
			}),
			array('db' => 'telefono', 'dt'=>4, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				$clientes       = Conexion::saber_permiso_asociado(7);# permisos
				if($clientes["editar"]){
					$res = '<div class="telefonoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
						'.((!empty($val))?$val:'------').'
					</div>
					<div id="T'.$idEncrip.'"></div>';
				}else{
					$res = ((!empty($val))?$val:'------');
				}
				return $res;
			}),
			array('db' => 'estado', 'dt'=>5, 'formatter'=>function($val, $fila){
				return (($val)?'
					<i class="btn btn-success btn-circle btn-lg">
                        <i class="fa fa-check"></i>
                    </i>':'
                    <i class="btn btn-danger btn-circle btn-lg">
                    	<i class="fa fa-close"></i>
                    </i>');
			}),
			array('db' => 'id', 'dt'=>6, 'formatter'=>function($val, $fila){
				$clientes       = Conexion::saber_permiso_asociado(7);# permisos
				return '
					<div class="dropdown mb-4">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            Opciones
                        </button>
                        <div class="dropdown-menu animated--fade-in"
                            aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="detalles-usuarios-'.Conexion::encriptar($val, "Tbl1").'">Ver detalles</a>
                            <a class="dropdown-item" href="../tienda-'.Conexion::encriptar($val, "Tbl1").'&1">Ir a la tienda</a>
							'.(($clientes["eliminar"])?'
                            	<hr>
								<a class="dropdown-item eliminar_usuario" href="javascript:" data-control="'.Conexion::encriptar($val, "Tbl1").'">Eliminar</a>
							':'').'
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
		if (in_array($_SESSION["SYSTEM"]["TIPO"], $pemitido)) {//permisos de usuario
			$id = Conexion::desencriptar($_POST["id"], "Tbl1");
			$datos = Usuarios::eliminar_usuario($id);
			$continue = $datos["proceso"];
			$mensaje = $datos["mensaje"];
		}else{
			$continue = false;
			$mensaje = "No tiene permisos para realizar esta acción";
		}
		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');

		break;
	case 'editar_usuario':
		$pemitido = array("MASTER","ADMIN");
		$continue = true;
		if (in_array($_SESSION["SYSTEM"]["TIPO"], $pemitido)) {//permisos de usuario
			$idEncrip = Conexion::formato_encript($_POST["id"], "des");
			$id = Conexion::decriptTable($idEncrip);
			$caso = $_POST["caso"];
			$valor = $_POST["valor"];
			$casos = array("usuario","nombre","apellido","correo","telefono");
			if (in_array($caso, $casos)) {
				$datos = Usuarios::editar_usuario($id, $caso, $valor);
				$continue = $datos["proceso"];
				$mensaje = $datos["mensaje"];
					
			}else{
				$continue = false;
				$mensaje = "Campo inválido";
			}
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