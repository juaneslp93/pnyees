<?php 
@session_start();
require "../model/conexion.php";
require "../model/ssp.php";
require '../model/mdl_productos.php';

$casos = array(
	"lista_productos",
	"eliminar_producto",
	"editar_producto",
	"crear_producto",
	"cargarDescuentos",
	"crear_descuento"
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
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '<div class="productoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="P'.$idEncrip.'"></div>';
			}),
			array('db' => 'descripcion', 'dt'=>1, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '<div class="descripcionEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="D'.$idEncrip.'"></div>';
			}),
			array('db' => 'precio', 'dt'=>2, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '<div class="precioEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="Pr'.$idEncrip.'"></div>';
			}),
			array('db' => 'impuesto', 'dt'=>3, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '<div class="impuestoEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="I'.$idEncrip.'"></div>';
			}),
			array('db' => 'url_imagen', 'dt'=>4, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return'<div class="imagenEditar" style="cursor:pointer;" data-control="'.$idEncrip.'">
					 '.((!empty($val))?$val:'------').'
				</div>
				<div id="M'.$idEncrip.'"></div>';
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
				$idEncrip = Conexion::encriptar($val, "Pro1");
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '
					<div class="dropdown mb-4">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            Opciones
                        </button>
                        <div class="dropdown-menu animated--fade-in"
                            aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="javascript:procesosListaProductos.cargarElementosAgregados(\''.$idEncrip.'\')">Descuentos</a>
                            <hr>
                            <a class="dropdown-item eliminar_producto" href="javascript:" data-control="'.$idEncrip.'">Eliminar</a>
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
			$idEncrip = Conexion::formato_encript($_POST["id"], "des");
			$id = Conexion::decriptTable($idEncrip);
			$caso = $_POST["caso"];
			$valor = $_POST["valor"];
			$casos = array("nombre","descripcion","impuesto","precio","url_imagen");

			if (in_array($caso, $casos)) {
				if ($caso==="url_imagen") {
					if (!empty($_FILES[$idEncrip]["tmp_name"])) {
		  				#proceso de imagen
		  				$imagen = Productos::procesar_imagen($_FILES, $idEncrip);
		  				$valor = (($imagen["existe"])?$imagen["url"]:'');
		  			}  
				}
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
  			$continue = true;
  			$imagen = array('existe' => false );

  			if (empty($nombre) || empty($precio) || empty($impuesto) ) {
  					$continue = false;
  					$mensaje = "El nombre, el precio y el impuesto son obligatorios";
  			}

  			if ($continue) {
	  			if (!empty($_FILES["imagenProducto"]["tmp_name"])) {
	  				#proceso de imagen
	  				$imagen = Productos::procesar_imagen($_FILES, 'imagenProducto');
	  			}  				
  			}

  			if ($continue) {
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
	case 'cargarDescuentos':
		$id =  Conexion::desencriptar($_POST["id"], "Pro1");
		$datos = Productos::cargar_descuentos($id);
		$result = array("continue" => true, "mensaje"=> 'consulta realizada', "elementos_agregados"=>$datos);
		break;
	case 'crear_descuento':
		$idEncrip = Conexion::formato_encript(@$_POST["id"], "des");
		$id = Conexion::desencriptar($idEncrip, "Pro1");		
		$min = @$_POST["min"];
		$max = @$_POST["max"];
		$descuento = @$_POST["descuento"];
		$continue = true;
		
		Productos::eliminar_descuentos($id);	

		if (count($descuento)>0) {
			foreach ($descuento as $key => $value) {

				if (empty($descuento[$key]) || empty($min[$key]) || empty($max[$key])) {
					$continue = false;
					$mensaje = "No se permiten valores vacíos ";
					break;
				}

				if ($continue) {
					if ($min>$max) {
						$continue = false;
						$mensaje = " El rango menor debe cumplir con dicha especificación ";
						break;
					}
				}

				if ($continue) {
					$reg = Productos::registrar_descuentos($id, $min[$key], $max[$key], $descuento[$key]);
					$continue = $reg["estado"];
					if ($reg["estado"]) {
						$mensaje = '<span class="text text-success"><h1 class="h4 text-gray-900 mb-4">¡Registro exitoso!</h1></span> ';
					}else{
						$mensaje = $reg["mensaje"];
					}
				}
			}
		}		

		$result = array("continue" => $continue, "mensaje"=> $mensaje, "url"=>'');
		break;
	default:
		$result = array("continue" => false, "mensaje"=> 'Método erróneo');
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