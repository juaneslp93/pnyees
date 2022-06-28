<?php 
include "../model/conexion.php";
include "../controller/ctr_scripts.php";
$recupera = false;
if(!empty($_GET["user"]) && $_GET["user"]!=='-'){
    $usuario = Conexion::desencriptar($_GET["user"], "C0rR");
    $recupera = true;
    if(is_numeric($usuario) && $usuario>0){
        Registro::aprobar_regenerar_contrasena($usuario);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <?= Scripts::headers(array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
    <style type="text/css" media="screen">
        .bg-password-image{
            background: url(assets/img/login.jfif)
        }
    </style>

</head>

<body class="bg-gradient-default">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <?php if(!$recupera){ ?>
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">¿No recuerdas tu contraseña?</h1>
                                        <hr>
                                        <p class="mb-4">Podemos ayudarte a recuperarla, por favor ingresa el correo que tienes registrado con nosptros 
                                            e inmediatemente te haremos llegar unan una contraseña provisional a ese mismo correo.
                                        </p>
                                    </div>
                                    <form class="user was-validated" id="formRecPass">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="recPass" name="correo" aria-describedby="emailHelp"
                                                placeholder="Ingrese su correo" required>
                                        </div>
                                        <input type="hidden" name="entrada" value="regPass">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Regenerar contraseña
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="registro">¡También puedes crear una cuenta!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?=URL_ABSOLUTA?>">Si ya tienes una cuenta, ¡inicia sesión!</a>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-2">¡Hemos aprobado tu petición!</h1>
                                            <hr>
                                            <p class="mb-4">Ahora tu contraseña se ha regenerado, por favor intenta acceder.<br>
                                                Te recomendamos que generes una contraseña propia, puedes hacerlo en la sección de perfil.
                                            </p>
                                        </div>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="registro">¡También puedes crear una cuenta!</a>
                                        </div>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="<?=URL_ABSOLUTA?>">Si ya tienes una cuenta, ¡inicia sesión!</a>
                                        </div>
                                        <hr>
                                        <br><br>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= Scripts::footers(array("jquery","bootstrap","sb-admin-2", "forgot")); ?> 

</body>

</html>