procesoEntrada = {
	preparar: function(){
		this.iniciar();
	},
	iniciar: function () {
		$("#FormLogin").submit(function(event){
			event.preventDefault();
			var self = this;
			$.ajax({
				url: 'controller/ctr_login.php',
				type: 'POST',
				dataType: 'json',
				data: $("#FormLogin").serialize(),
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
					  icon: 'error',
					  title: '¡Ups...!',
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