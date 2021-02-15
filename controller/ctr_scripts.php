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
			"fontAwesome"=>'<link href="'.$ruta.'assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">',
			"fonts.googleapis"=>'<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">',
			"sb-admin-2"=>'<link href="'.$ruta.'assets/css/sb-admin-2.min.css" rel="stylesheet">'
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
		);

		$contenido = '
		<!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sykeyns_software '.date('Y').'</span>
                    </div>
                </div>
            </footer>';
		for ($i=0; $i <count($peticion) ; $i++) { 
			if (!empty($peticion[$i])) {
				$contenido.=$elementos["".$peticion[$i]];
			}
		}
		return $contenido;
	}
}
?>