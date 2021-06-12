procesoPagos = {
	iniciar: function(){
		this.validar_datos_facturacion();
		this.cargar_botones_de_pago();
	},
	cargar_botones_de_pago: function(){
		$("#botones").html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'botonesPasarela'},
		})
		.done(function(result) {
			console.log(result)
		})
		.fail(function() {
			console.log("error");
		});	
		
	},
	validar_datos_facturacion: function(){

	}
}

jQuery(document).ready(function($) {
	procesoPagos.iniciar();
});