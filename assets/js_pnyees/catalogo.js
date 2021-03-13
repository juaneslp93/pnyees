procesoCatalogo = {
	iniciar: function(){
		this.cargarCatalogo();
	},
	cargarCatalogo: function(){
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarCatalogo'},
		})
		.done(function(result) {
			if (result.continue) {
				$("#catalogo").html(result.html);
				procesoTienda.cargar_forms();
			}
		})
		.fail(function() {
			console.log("error");
		});
		
	}
}


jQuery(document).ready(function($) {
	procesoCatalogo.iniciar();	
});