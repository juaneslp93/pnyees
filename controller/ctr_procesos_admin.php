<?php 
session_start();
require '../model/conexion.php';
require '../model/mdl_procesos_admin.php';
#Definición de entradas
$casos = array(
	"datosInicio",
	"notificaciones",
	"cargarDatosGrafico",
	"cargarInfoDetallesUsuarios"
);
// entrada
$caso = '';
if (!empty($_POST)) {
	if (in_array($_POST["entrada"], $casos)) {
		$caso = $_POST["entrada"];
	}
}

switch ($caso) {
	case 'datosInicio':
		
		$datos = ProcesosAdmin::datos_dashboard();

		$result = array("continue" => true, "datos"=>$datos);
		break;

	case 'notificaciones':
		
		$datos = ProcesosAdmin::datos_notificaciones();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarDatosGrafico':
		$datos = ProcesosAdmin::datos_grafico();

		$result = array("continue" => true, "datos"=>$datos);
		break;
	case 'cargarInfoDetallesUsuarios':
		$id = Conexion::desencriptar($_POST["id"], 'Tbl1');
		$datos = ProcesosAdmin::datos_detalles_usuarios($id);
		$usuario = $datos["usuario"];
		$nommbre_completo = $datos["nommbre_completo"];
		$correo = $datos["correo"];
		$total_comprado = number_format($datos["total_comprado"],0,'',',');
		$total_cantidad = (($datos["total_cantidad"]>0)?$datos["total_cantidad"]:0);
		$fecha_registro = ProcesosAdmin::validar_fecha($datos["fecha_registro"]);
		$estado = (($datos["estado"]=='1')?'
					<i class="btn btn-success btn-circle btn-lg">
                        <i class="fa fa-check"></i> Activo
                    </i>':'
                    <i class="btn btn-danger btn-circle btn-lg">
                    	<i class="fa fa-close"></i> Eliminado
                    </i>');
		$html = '
				<div class="row">
			        <div class="col-sm-2 col-md-2">
			            <img src="../assets/img/profile.jfif"
			            alt="" class="img-rounded img-responsive img-thumbnail" />
			        </div>
			        <div class="col-sm-4 col-md-4">
			            <blockquote>
			                <h1> '.$usuario.'</h1> <cite title="Source Title"><i class="fa fa-user"></i> '.$nommbre_completo.' </cite>
			            </blockquote>
			            <p> <i class="fa fa-envelope"></i> '.$correo.'
			                <br /> <i class="fa fa-shopping-cart"></i> Total comprado: $'.$total_comprado.'
			                <br /> <i class="fa fa-tachometer"></i> Total Metros<sup>2</sup>: '.$total_cantidad.'
			                <br /> <i class="fa fa-calendar"></i> '.$fecha_registro.'</p>
			        </div>
			    </div>';
		$result = array("continue" => true, "html"=>$html);
		break;
	
	default:
		echo 'NADA';
		break;
}

echo json_encode($result);

?>