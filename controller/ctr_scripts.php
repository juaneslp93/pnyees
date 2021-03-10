<?php 
/**
 * 
 */
class Scripts
{
	
	function __construct()	{
		# code...
	}

	public static function headers($ruta, $peticion=array("fontAwesome","fonts.googleapis","sb-admin-2"))	{
		
		$elementos = array(
			"fontAwesome"=>'<link href="'.$ruta.'assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
				<link href="'.$ruta.'assets/vendor/fontawesome-free/css/v4-shims.min.css" rel="stylesheet" type="text/css">',
			"fonts.googleapis"=>'<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">',
			"sb-admin-2"=>'<link href="'.$ruta.'assets/css/sb-admin-2.min.css" rel="stylesheet">',
			"dataTables"=>'<link href="'.$ruta.'assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">'
		);

		$contenido = '
			<meta charset="utf-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		    <meta name="description" content="">
		    <meta name="author" content="">
		    <link rel="icon" type="image/png" href="'.$ruta.'imagen.png">
		    <title>Pnyees</title>
    	';
		for ($i=0; $i <count($peticion) ; $i++) { 
			if (!empty($peticion[$i])) {
				$contenido.=$elementos["".$peticion[$i]];
			}
		}
		return $contenido;
	}

	public static function footers($ruta, $peticion=array("jquery","bootstrap","sb-admin-2"))	{
	
		$elementos = array(
			"jquery"=>'<script src="'.$ruta.'assets/vendor/jquery/jquery.min.js"></script>
						<script src="'.$ruta.'assets/vendor/jquery-easing/jquery.easing.min.js"></script>',
			"bootstrap"=>'<script src="'.$ruta.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
						<script src="'.$ruta.'assets/vendor/bootstrap/js/sweetalert2.all.min.js"></script>',
			"sb-admin-2"=>'<script src="'.$ruta.'assets/js/sb-admin-2.min.js"></script>',
			"chart"=>'<script src="'.$ruta.'assets/vendor/chart.js/Chart.min.js"></script>
					<script src="'.$ruta.'assets/js/demo/chart-area-demo.js"></script>
    				<script src="'.$ruta.'assets/js/demo/chart-pie-demo.js"></script>',
    		"dataTables"=>'<!-- Page level plugins -->
				    <script src="'.$ruta.'assets/vendor/datatables/jquery.dataTables.min.js"></script>
				    <script src="'.$ruta.'assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>',
			"system"=>'<script src="'.$ruta.'assets/js_pnyees/procesos_admin.js" type="text/javascript"></script>',
			"system-user"=>'<script src="'.$ruta.'assets/js_pnyees/procesos_user.js" type="text/javascript"></script>',
			"charts"=>'<!-- Page level plugins -->
    				<script src="'.$ruta.'assets/vendor/chart.js/Chart.min.js"></script>,
    				<script src="'.$ruta.'assets/js_pnyees/chart.js"></script>',
    		"productos_lista"=>'<script src="'.$ruta.'assets/js_pnyees/lista_productos.js" type="text/javascript" ></script>',
    		"usuarios_detalles"=>'<script src="'.$ruta.'assets/js_pnyees/usuarios_detalles.js" type="text/javascript" charset="utf-8"></script>',
    		"usuarios_lista"=>'<script src="'.$ruta.'assets/js_pnyees/lista_usuarios.js" type="text/javascript" ></script>',
    		"login"=>'<script src="'.$ruta.'assets/js_pnyees/login.js"></script>',
    		"registro"=>'<script src="'.$ruta.'assets/js_pnyees/registro.js" type="text/javascript" charset="utf-8"></script>',
    		"catalogo"=>'<script src="'.$ruta.'assets/js_pnyees/catalogo.js" type="text/javascript" charset="utf-8"></script>',
    		"detalle_producto"=>'<script src="'.$ruta.'assets/js_pnyees/detalle_producto.js" type="text/javascript" charset="utf-8"></script>',
    		"proceso_tienda"=>'<script src="'.$ruta.'assets/js_pnyees/proceso_tienda.js" type="text/javascript" charset="utf-8"></script>'
		);

		$contenido = '
		<!-- Scroll to Top Button-->
	    <a class="scroll-to-top rounded" href="#page-top">
	        <i class="fas fa-angle-up"></i>
	    </a>
	    
		<!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sykeyns software &reg; '.date('Y').'</span>
                    </div>
                </div>
            </footer>

            <!-- Logout Modal-->
		    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutMdl"
		        aria-hidden="true">
		        <div class="modal-dialog" role="document">
		            <div class="modal-content">
		                <div class="modal-header">
		                    <h5 class="modal-title" id="logoutMdl">¿Seguro de cerrar la sesión actual?</h5>
		                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
		                        <span aria-hidden="true">×</span>
		                    </button>
		                </div>
		                <div class="modal-body">Seleccione "Cerrar" si está seguro de continuar.</div>
		                <div class="modal-footer">
		                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
		                    <a class="btn btn-primary" href="cerrar">Cerrar</a>
		                </div>
		            </div>
		        </div>
		    </div>
    ';
		for ($i=0; $i <count($peticion) ; $i++) { 
			if (!empty($peticion[$i])) {
				$contenido.=$elementos["".$peticion[$i]];
			}
		}
		return $contenido;
	}
}
?>