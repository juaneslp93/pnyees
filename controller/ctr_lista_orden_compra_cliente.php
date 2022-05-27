<?php 
@session_start();
require "../model/conexion.php";
require "../model/ssp.php";
require '../model/mdl_ordenes_compra.php';

$casos = array(
	"lista_orden_compra",
	"procesarOrdenCompra",
	"detalleOrdenCompra"
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
	case 'lista_orden_compra':
		$table = "ordenes_compras";
		$primaryKey = "id";
		$columns  = array(
			array('db' => 'numero_orden', 'dt'=>0, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '
					<div>
						<a href="orden-compra-detalle-'.$idEncrip.'" title="numero de orden">'.$fila["numero_orden"].'</a>
					</div>
				';
			}),
			array('db' => 'id_usuario', 'dt'=>1, 'formatter'=>function($val, $fila){
				$usuario = OrdenesCompra::saber_nombre_usuario($fila["id_usuario"]);
				return $usuario;
			}),
			array('db' => 'total_orden_compra', 'dt'=>2),
			array('db' => 'total_descuento', 'dt'=>3),
			array('db' => 'total_impuesto', 'dt'=>4),
			array('db' => 'metodo_pago', 'dt'=>5, 'formatter'=>function($val, $fila){
				$metodo = OrdenesCompra::saber_metodo_pago($fila["metodo_pago"]);
				return $metodo;
			}),
			array('db' => 'fecha', 'dt'=>6),
			array('db' => 'estado_proceso', 'dt'=>7, 'formatter'=>function($val, $fila){
				return '
					<div>
						'.(($fila["estado_proceso"]=='1')?'
							<i class="btn btn-success btn-circle">
								<i class="fa fa-check"></i>
							</i>
						':'
							<i class="btn btn-danger btn-circle">
								<i class="fa fa-close"></i>
							</i>
						').'
					</div>';
			}),
			array('db' => 'estado_aprobacion', 'dt'=>8, 'formatter'=>function($val, $fila){
				return '
					<div>
						'.(($fila["estado_aprobacion"]=='1')?'
							<i class="btn btn-success btn-circle">
								<i class="fa fa-check"></i>
							</i>
						':'
							<i class="btn btn-danger btn-circle">
								<i class="fa fa-close"></i>
							</i>
						').'
					</div>';
			}),			
			array('db' => 'id', 'dt'=>9),
		);
		$conexion = Conexion::iniciar();
		$sql_details = Conexion::dataTable(KEYGEN_DATATBLE);
		$where = ' id_usuario ='.Conexion::desencriptar($_SESSION["SYSTEM"]["ID"], "Tbl1");
		$data = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where, '' );
		$conexion->close();
		break;
	case 'procesarOrdenCompra':
		$opcion = @$_POST["opcion-orden"];
		$ordenes = @$_POST["autorizarOrden"];
		$continuar = true;

		if (empty($opcion)) {
			$continuar = false;
			$mensaje = "No ha seleccionado una opción de procesamiento";
		}

		if ($continuar) {
			if (empty($ordenes)) {
				$continuar = false;
				$mensaje = "No ha seleccionado una orden de compra para procesar";
			}
		}

		if ($continuar) {			
			$datos = OrdenesCompra::procesar_orden_compra($ordenes, $opcion);
			$continuar = $datos["result"];
			$mensaje = $datos["mensaje"];
		}

		$result = array("continue" => $continuar, "mensaje"=> $mensaje);
		break;
	case 'detalleOrdenCompra':
		$idOrden = $_POST["idOrden"];		
		$datos = OrdenesCompra::cargar_detalle_orden_compra($idOrden);
		$result = array("continue" => $datos["result"], "mensaje"=> $datos["mensaje"], "html"=>$datos["html"]);
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