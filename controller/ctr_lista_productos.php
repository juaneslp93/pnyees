<?php 
session_start();
require "../model/conexion.php";
require "../model/ssp.php";
require '../model/mdl_productos.php';

$casos = array(
	"lista_productos",
	"eliminar_producto",
	"editar_producto",
	"crear_producto"
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
	case 'lista_productos':
		# code...
		$table = "productos";
		$primaryKey = "id";
		$columns  = array(
			array('db' => 'nombre', 'dt'=>0, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = preg_replace('~=~', '-', $idEncrip);
				return '<div class="productoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="P'.$idEncrip.'"></div>';
			}),
			array('db' => 'descripcion', 'dt'=>1, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = preg_replace('~=~', '-', $idEncrip);
				return '<div class="descripcionEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="D'.$idEncrip.'"></div>';
			}),
			array('db' => 'precio', 'dt'=>2, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = preg_replace('~=~', '-', $idEncrip);
				return '<div class="precioEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="Pr'.$idEncrip.'"></div>';
			}),
			array('db' => 'impuesto', 'dt'=>3, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = preg_replace('~=~', '-', $idEncrip);
				return '<div class="impuestoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="I'.$idEncrip.'"></div>';
			}),
			array('db' => 'url_imagen', 'dt'=>4, 'formatter'=>function($val, $fila){
				$valor = str_replace("../uploads/", "", $val);
				return((!empty($valor))?$valor:'------');
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
				return '
					<div class="dropdown mb-4">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            Opciones
                        </button>
                        <div class="dropdown-menu animated--fade-in"
                            aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="detalles-producto-'.Conexion::encriptar($val, "Pro1").'">Ver detalles</a>
                            <hr>
                            <a class="dropdown-item eliminar_producto" href="javascript:" data-control="'.Conexion::encriptar($val, "Pro1").'">Eliminar</a>
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
	case 'eliminar_producto':
		$pemitido = array("MASTER","ADMIN");
		$continue = true;
		if (in_array($_SESSION["SYSTEM"]["TIPO"], $pemitido)) {//permisos de usuario
			$id = Conexion::desencriptar($_POST["id"], "Pro1");
			$datos = Productos::eliminar_producto($id);
			$continue = $datos["proceso"];
			$mensaje = $datos["mensaje"];
		}else{
			$continue = false;
			$mensaje = "No tiene permisos para realizar esta acción";
		}
		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');

		break;
	case 'editar_producto':
		$pemitido = array("MASTER","ADMIN");
		$continue = true;
		if (in_array($_SESSION["SYSTEM"]["TIPO"], $pemitido)) {//permisos de usuario
			$idEncrip = preg_replace('~-~', '=', $_POST["id"]);
			$id = Conexion::decriptTable($idEncrip);
			$caso = $_POST["caso"];
			$valor = $_POST["valor"];
			$casos = array("nombre","descripcion","impuesto","precio");
			if (in_array($caso, $casos)) {
				// echo "$id $caso $valor";
				$datos = Productos::editar_producto($id, $caso, $valor);
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
	case 'crear_producto':
		$pemitido = array("MASTER","ADMIN");
		$continue = true;
		if (in_array($_SESSION["SYSTEM"]["TIPO"], $pemitido)) {//permisos de usuario
			$nombre = $_POST["nombre"];
  			$precio = $_POST["precio"];
  			$impuesto = $_POST["impuesto"];
  			$descripcion = $_POST["descripcion"];
  			$ruta = '';
  			$continuar = true;
  			$imagen = array('existe' => false );

  			if (empty($nombre) || empty($precio) || empty($impuesto) ) {
  					$continuar = false;
  					$mensaje = "El nombre, el precio y el impuesto son obligatorios";
  			}

  			if ($continuar) {
	  			if (!empty($_FILES["imagenProducto"]["tmp_name"])) {
	  				#proceso de imagen
	  				$imagen = Productos::procesar_imagen($_FILES);
	  			}  				
  			}

  			if ($continuar) {
  				$reg = Productos::registrar_producto($nombre, $precio, $impuesto, $descripcion, (($imagen["existe"])?$imagen["url"]:''));
  				$continue = $reg["estado"];
  				if ($reg["estado"]) {
  					$mensaje = '<span class="text text-success"><h1 class="h4 text-gray-900 mb-4">¡Registro exitoso!</h1></span> ['.$imagen["mensaje"].']';
  				}else{
  					$mensaje = $reg["mensaje"];
  				}
  			}

  			$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');

			
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