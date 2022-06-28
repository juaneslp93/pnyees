<?php 
@session_start();
require "../model/conexion.php";
require "../model/ssp.php";
require '../model/mdl_compra.php';
$casos = array(
	"lista_compra",
	"detalleCompra",
	"generar_pdf"
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
	case 'lista_compra':
		$table = "compras";
		$primaryKey = "id";
		$columns  = array(
			array('db' => 'nro_compra', 'dt'=>0, 'formatter'=>function($val, $fila){
				$idEncrip = Conexion::encriptTable($fila["id"]);
				$idEncrip = Conexion::formato_encript($idEncrip, "con");
				return '
					<div>
						<a href="compra-detalle-'.$idEncrip.'" title="numero de factura">'.Conexion::formato_nro_factura($fila["nro_compra"]).'</a>
					</div>
				';
			}),
			array('db' => 'id_usuario', 'dt'=>1, 'formatter'=>function($val, $fila){
				$usuario = Compras::saber_nombre_usuario($fila["id_usuario"]);
				return $usuario;
			}),
			array('db' => 'total_compra', 'dt'=>2, 'formatter'=>function($val, $fila){
				return Conexion::formato_decimal($fila["total_compra"]);
			}),
			array('db' => 'total_descuento', 'dt'=>3),
			array('db' => 'total_impuesto', 'dt'=>4),
			array('db' => 'metodo_pago', 'dt'=>5, 'formatter'=>function($val, $fila){
				$metodo = Compras::saber_metodo_pago($fila["metodo_pago"]);
				return $metodo;
			}),
			array('db' => 'fecha_compra', 'dt'=>6),
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
			array('db' => 'estado_envio', 'dt'=>9, 'formatter'=>function($val, $fila){
				return '
					<div>
						'.(($fila["estado_envio"]=='1')?'
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
			array('db' => 'id', 'dt'=>10),
		);
		$conexion = Conexion::iniciar();
		$sql_details = Conexion::dataTable(KEYGEN_DATATBLE);
		$where = ' id_usuario ='.Conexion::desencriptar($_SESSION["SYSTEM"]["ID"], "Tbl1");
		$data = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where, '' );
		$conexion->close();
		break;
	
		if($comprasPermiso["editar"]){
			$opcion = @$_POST["opcion-compra"];
			$compras = @$_POST["seleccionCompra"];
			$continuar = true;

			if (empty($opcion)) {
				$continuar = false;
				$mensaje = "No ha seleccionado una opción de procesamiento";
			}

			if ($continuar) {
				if (empty($compras)) {
					$continuar = false;
					$mensaje = "No ha seleccionado una compra para procesar";
				}
			}

			if ($continuar) {			
				$datos = Compras::procesar_compra($compras, $opcion);
				$continuar = $datos["result"];
				$mensaje = $datos["mensaje"];
			}

			$result = array("continue" => $continuar, "mensaje"=> $mensaje);
		}else{
			$result = array("continue" => false, "mensaje"=> 'No tiene suficientes permisos');
		}
		
		break;
	case 'detalleCompra':
		$idCompra = $_POST["idCompra"];		
		$datos = Compras::cargar_detalle_compra($idCompra);
		$result = array("continue" => $datos["result"], "mensaje"=> $datos["mensaje"], "html"=>$datos["html"]);
		break;
	case 'generar_pdf':
		$idCompra = $_POST["id"];
		Compras::cargar_pdf_factura($idCompra);
		$result = array("continue" => true, "mensaje"=> "Pdf generado");
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