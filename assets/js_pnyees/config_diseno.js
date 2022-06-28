$.fn.exists = function () {
    return this.length !== 0;
}
configDiseno = {
    iniciarConfig: function () {
        this.cargaXdefecto();
    },
    cargaXdefecto: function () {
		var self = this;
        $.ajax({
            url: '../controller/ctr_procesos_admin.php',
            type: 'POST',
            dataType: 'json',
            data: {'entrada': 'cargarDiseno'},
            success: function (response) {
                $("#cotentDiseno").html(response.html);
				self.editarDisenoForm();
            }
        });
    },
	editarDisenoForm: function(){
		$("body").on("submit", "#formEditDiseno", function(e){
			e.preventDefault();
			$("button").attr("disabled", "disabled");
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: $("#formEditDiseno").serialize(),
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  html: result.mensaje
					})
					window.location.reload();
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}
			})
			.fail(function() {
				console.log("error");
			})
			.always(function(){
				$("button").removeAttr("disabled");
			});	
		});
	}
}

jQuery(document).ready(function($) {
    configDiseno.iniciarConfig();
});