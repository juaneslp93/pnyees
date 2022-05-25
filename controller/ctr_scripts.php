<?php 
/**
 * 
 */
class Scripts
{
	
	function __construct()	{
		# code...
	}

	public static function headers($peticion=array("fontAwesome","fonts.googleapis","sb-admin-2"))	{
		$ruta = URL_ABSOLUTA;
		$elementos = array(
			"fontAwesome"=>'<link href="'.$ruta.'assets/vendor/fontawesome-free/css/all.min.css?'.self::keyCache(2).'" rel="stylesheet" type="text/css">
				<link href="'.$ruta.'assets/vendor/fontawesome-free/css/v4-shims.min.css?'.self::keyCache(2).'" rel="stylesheet" type="text/css">',
			"fonts.googleapis"=>'<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">',
			"sb-admin-2"=>'<link href="'.$ruta.'assets/css/sb-admin-2.min.css?'.self::keyCache(2).'" rel="stylesheet">
							<link href="'.$ruta.'assets/css/switch.css?'.self::keyCache(2).'" rel="stylesheet">',
			"dataTables"=>'<link href="'.$ruta.'assets/vendor/datatables/datatables.min.css?'.self::keyCache(2).'" rel="stylesheet">'
		);

		$contenido = '
			<meta charset="utf-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		    <meta name="description" content="">
		    <meta name="author" content="">
		    <link rel="icon" type="image/png" href="'.$ruta.'assets/img/icono.ico">
		    <title>Pnyees</title>			
    	';
		for ($i=0; $i <count($peticion) ; $i++) { 
			if (!empty($peticion[$i])) {
				$contenido.=$elementos["".$peticion[$i]];
			}
		}
		#estilos personalizados
		$contenido.= '
			<style type="text/css" media="screen">
				::selection {
					background: black !important;
					color: white !important;
				}
				/* Firefox */
				::-moz-selection {
					background: black !important;
					color: white !important;
				}
			</style>
		';
		return $contenido;
	}

	public static function footers($peticion=array("jquery","bootstrap","sb-admin-2"))	{
		$ruta = URL_ABSOLUTA;
		$elementos = array(
			"jquery"=>'<script src="'.$ruta.'assets/vendor/jquery/jquery.min.js?'.self::keyCache(2).'"></script>
						<script src="'.$ruta.'assets/vendor/jquery-easing/jquery.easing.min.js?'.self::keyCache(2).'"></script>',
			"bootstrap"=>'<script src="'.$ruta.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js?'.self::keyCache(2).'"></script>
						<script src="'.$ruta.'assets/vendor/bootstrap/js/sweetalert2.all.min.js?'.self::keyCache(2).'"></script>',
			"sb-admin-2"=>'<script src="'.$ruta.'assets/js/sb-admin-2.min.js?'.self::keyCache(2).'"></script>',
			"chart"=>'<script src="'.$ruta.'assets/vendor/chart.js/Chart.min.js?'.self::keyCache(2).'"></script>
					<script src="'.$ruta.'assets/js/demo/chart-area-demo.js?'.self::keyCache(2).'"></script>
    				<script src="'.$ruta.'assets/js/demo/chart-pie-demo.js?'.self::keyCache(2).'"></script>',
    		"dataTables"=>'<!-- Page level plugins -->
				    <script src="'.$ruta.'assets/vendor/datatables/datatables.min.js?'.self::keyCache(2).'"></script>',
			"system"=>'<script src="'.$ruta.'assets/js_pnyees/procesos_admin.js?'.self::keyCache(2).'"></script>',
			"system-user"=>'<script src="'.$ruta.'assets/js_pnyees/procesos_user.js?'.self::keyCache(2).'" ></script>',
			"charts"=>'<!-- Page level plugins -->
    				<script src="'.$ruta.'assets/vendor/chart.js/Chart.min.js?'.self::keyCache(2).'"></script>,
    				<script src="'.$ruta.'assets/js_pnyees/chart.js?'.self::keyCache(2).'"></script>',
    		"productos_lista"=>'<script src="'.$ruta.'assets/js_pnyees/lista_productos.js?'.self::keyCache(2).'"  ></script>',
    		"usuarios_detalles"=>'<script src="'.$ruta.'assets/js_pnyees/usuarios_detalles.js?'.self::keyCache(2).'" ></script>',
    		"usuarios_lista"=>'<script src="'.$ruta.'assets/js_pnyees/lista_usuarios.js?'.self::keyCache(2).'"  ></script>',
    		"login"=>'<script src="'.$ruta.'assets/js_pnyees/login.js?'.self::keyCache(2).'"></script>',
    		"registro"=>'<script src="'.$ruta.'assets/js_pnyees/registro.js?'.self::keyCache(2).'" ></script>',
    		"catalogo"=>'<script src="'.$ruta.'assets/js_pnyees/catalogo.js?'.self::keyCache(2).'" ></script>',
    		"detalle_producto"=>'<script src="'.$ruta.'assets/js_pnyees/detalle_producto.js?'.self::keyCache(2).'" ></script>',
    		"proceso_tienda"=>'<script src="'.$ruta.'assets/js_pnyees/proceso_tienda.js?'.self::keyCache(2).'" ></script>',
    		"proceso_resumen"=>'<script src="'.$ruta.'assets/js_pnyees/proceso_resumen.js?'.self::keyCache(2).'" ></script>',
    		"proceso_pagos"=>'<script src="'.$ruta.'assets/js_pnyees/proceso_pagos.js?'.self::keyCache(2).'" ></script>',
    		"proceso_medios_pago"=>'<script src="'.$ruta.'assets/js_pnyees/proceso_medios_pago.js?'.self::keyCache(2).'"></script>',
    		"orden_compra_lista"=>'<script src="'.$ruta.'assets/js_pnyees/lista_orden_compra.js?'.self::keyCache(2).'"></script>',
			"orden_compra_detalle"=>'<script src="'.$ruta.'assets/js_pnyees/detalle_orden_compra.js?'.self::keyCache(2).'"></script>',
			"compra_lista"=>'<script src="'.$ruta.'assets/js_pnyees/lista_compra.js?'.self::keyCache(2).'"></script>',
			"compra_detalle"=>'<script src="'.$ruta.'assets/js_pnyees/detalle_compra.js?'.self::keyCache(2).'"></script>',
			"config-general"=>'<script src="'.$ruta.'assets/js_pnyees/config_general.js?'.self::keyCache(2).'"></script>',
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
		#scripts personalizados
		$contenido .= '
		<script>
			window.onload = function(){
				function init(){
					//carga de documento
					var carg = document.getElementById("carga-global");
					var pa = document.getElementById("wrapper");
					pa.removeAttribute("style");
					carg.setAttribute("style", "display:none;")
					// configuración del menu
					var elem = document.getElementById("page-top");
					elem.classList.add("sidebar-toggled");
					var elem2 = document.getElementById("accordionSidebar");
					elem2.classList.add("toggled");
				}
				init();
				
			}
		</script>
		';
		return $contenido;
	}

	private static function keyCache($cantidad)	{		 
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle($permitted_chars), 0, $cantidad);
	}
}
?>