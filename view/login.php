<?php 
include "controller/ctr_scripts.php";
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Custom fonts for this template-->
    <?= Scripts::headers(array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
    <style type="text/css" media="screen">
        .bg-login-image{
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
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center" id="mensaje">
                                        <h1 class="h4 text-gray-900 mb-4">¡Ingresa tus datos!</h1>
                                        <hr>
                                    </div>
                                    <form class="user" name="FormLogin" id="FormLogin">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="usuario" name="usuario"  placeholder="usuario" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="clave" name="clave"  placeholder="contraseña" required>
                                        </div>
                                        
                                        <input type="hidden" name="entrada" value="ingresoSistema">
                                        <button type="submit" class="btn btn-dark btn-user btn-block">
                                            <i class="fa fa-sign-in fa-lg"></i> Ingresar
                                        </button>
                                        <hr>
                                        <a href="tienda-YQ==&1" class="btn btn-secondary btn-user btn-block"><i class="fa fa-shopping-cart fa-lg"></i> Ir directo a la tienda</a>
                                       
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">¿olvidó la contraseña?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="registro">¡Crea una cuenta!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?= Scripts::footers(array("jquery","bootstrap","sb-admin-2", "login")); ?> 

</body>

</html>