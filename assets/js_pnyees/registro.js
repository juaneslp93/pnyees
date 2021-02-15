procesoRegistro = {
	prepararRegistro: function(){
		this.iniciar();
		this.validar_claves();
	},
	iniciar: function () {
		$("#FormRegistro").submit(function(event){
			event.preventDefault();
			var self = this;
			$.ajax({
				url: 'controller/ctr_registro.php',
				type: 'POST',
				dataType: 'json',
				data: $("#FormRegistro").serialize(),
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso completado!',
					  html: result.mensaje
					}).then(function(){
						document.getElementById("FormRegistro").reset();
					})
				}else{
					Swal.fire({
					  icon: 'error',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}
			})
			.fail(function() {
				console.log("error");
			});
		});
	},
	validar_claves: function(){
		$("#repita_clave").blur(function(event) {
			if ($(this).val() !== $("#clave").val()) {
				Swal.fire({
					  icon: 'warning',
					  title: '¡Disculpe!',
					  text: 'Las contraseñas no coinciden'
					})
			}else{
				$.ajax({
					url: 'controller/ctr_registro.php',
					type: 'POST',
					dataType: 'json',
					data: {'entrada':'claveSeguraValidar', 'clave': $("#clave").val()},
				})
				.done(function(result) {
					if (!result.continue) {
					
						Swal.fire({
						  icon: 'warning',
						  title: '¡Disculpe!',
						  html: result.mensaje
						})
					}
				})
				.fail(function() {
					console.log("error");
				});
				
			}
		});
	}
}