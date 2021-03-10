procesoDetalle = {
	iniciar: function(){
		this.cargarDetalle();
	},
	cargarDetalle: function(){
		var valor = $("#referencia").val();
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarDetalle', "id":valor},
		})
		.done(function(result) {
			if (result.continue) {
				$("#catalogo").html(result.html);
			}
		})
		.fail(function() {
			console.log("error");
		});
		
	}
}


jQuery(document).ready(function($) {
	procesoDetalle.iniciar();	
});