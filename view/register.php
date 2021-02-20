<?php 
include "../controller/ctr_scripts.php";

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Scripts::headers('', array("fontAwesome","fonts.googleapis","sb-admin-2")); ?> 
</head>

<body class="bg-gradient-primary">

    <div class="container">
        
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">¡Crear una cuenta!</h1>
                            </div>
                            <form class="user" id="FormRegistro">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="usuario" id="usuario"
                                            placeholder="Usuario" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="clave" name="clave" placeholder="Clave" required>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="repita_clave" id="repita_clave"
                                            placeholder="Repita clave" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user"
                                            id="nombre" name="nombre" placeholder="Nombre" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="apellido" name="apellido"
                                        placeholder="Apellido" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control form-control-user"
                                            id="telefono" name="telefono" placeholder="Teléfono / Celular" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control form-control-user" id="correo" name="correo"
                                        placeholder="Correo electrónico" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <input type="hidden" name="entrada" value="registroSistema">
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registrar cuenta
                                </button>
                                <hr>
                                
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="../">¿Ya tienes una cuenta? ¡Inicia sesión!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?= Scripts::footers('', array("jquery","bootstrap","sb-admin-2","system")); ?> 
    <script src="assets/js_pnyees/registro.js" type="text/javascript" charset="utf-8"></script>
    <script>
        jQuery(document).ready(function($) {
            procesoRegistro.prepararRegistro();
        });
    </script>
</body>

</html>