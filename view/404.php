<?php 
include "../model/setup.php";
include "../controller/ctr_scripts.php";
$sitio = URL_ABSOLUTA;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?= Scripts::headers(array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- 404 Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="404">404</div>
                        <p class="lead text-gray-800 mb-5">Página no encontrada.</p>
                        <p class="text-gray-500 mb-0">No hemos encontrado la página que solicitas. Si crees que es un error comentalo con el administrador del sitio.</p>
                        <a href="javascript: Back.page();">Volver atrás</a>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?= Scripts::footers(array("jquery","bootstrap","sb-admin-2","system")); ?> 
    <script>
        Back = {
            page: function(){
                var numeroDeEntradas = window.history.length;
                var redirect = '';
                if(numeroDeEntradas>0 && window.history.go(-1)!=undefined){
                    redirect =  window.history.go(-1);
                }else{
                    redirect = '<?= $sitio ?>';
                }
                
                window.location = redirect;
            }
        }
        
        
    </script>
</body>

</html>