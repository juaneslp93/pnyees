procesoTienda = {
	iniciarTienda: function(){
		this.agregarCarrito();
	},
	agregarCarrito: function(){
		$("body").on('click','.agregarCarrito', function () {
			console.log($(this).attr("data-control"))
		});
	}
}

jQuery(document).ready(function($) {
	procesoTienda.iniciarTienda();
});