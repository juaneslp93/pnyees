procesoEntrada = {
	preparar: function(){
		this.iniciar();
	},
	iniciar: function () {
		$("#formRecPass").submit(function(event){
			event.preventDefault();
			var self = this;
			$.ajax({
				url: 'controller/ctr_registro.php',
				type: 'POST',
				dataType: 'json',
				data: $("#formRecPass").serialize(),
			})
			.done(function(result) {			
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  html: result.mensaje
					})
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}	
				if (result.url) {
					window.location = result.url;
				}				
			})
			.fail(function() {
				console.log("error");
			});
		});
	}
}

jQuery(document).ready(function($) {
    procesoEntrada.preparar();
});