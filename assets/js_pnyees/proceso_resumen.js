procesoResumen = {
	iniciar: function(){
		this.cargar_vaciado();
	},
	eliminarProducto: function(id){
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'eliminarProducto', 'id':id},
		})
		.done(function(result) {
			window.location.reload();
		})
		.fail(function() {
			console.log("error");
		});
		
	},
	cargar_vaciado: function(){
		$("#vaciar").on("click",function(){
			$.ajax({
				url: 'controller/ctr_catalogo.php',
				type: 'POST',
				dataType: 'json',
				data: {'entrada': 'vaciarCarrito'},
			})
			.done(function(result) {
				window.location.reload();
			})
			.fail(function() {
				console.log("error");
			});
		})
	}
}

jQuery(document).ready(function($) {
	procesoResumen.iniciar();
});