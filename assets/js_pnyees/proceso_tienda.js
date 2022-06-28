procesoTienda = {
	iniciarTienda: function(){
		this.actualizarInfo();
		/*this.cargarInfoResumen()*/
	},
	agregarCarrito: function(element){
		var self = this;
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: $(element).serialize(),
		})
		.done(function(result) {
			if (result.continue) {
				Swal.fire({
					icon: 'success',
					title: '',
					text: '¡Producto agregado!'
				})
				self.actualizarInfo();
			}else{
				Swal.fire({
					icon: 'warning',
					title: '',
					html: result.mensaje
				})
			}
		})
		.fail(function() {
			console.log("error");
		});
		
	},
	cargar_forms: function(){
		var self = this;
		$("form[name=FormAgregarCarrito]").each(function(index, elemento){
			$(this).submit(function(event){		
				event.preventDefault();
				self.agregarCarrito(this);
				$(this)[0].reset();
			});
		})
	},
	actualizarInfo: function(){
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'actualizarCot'},
		})
		.done(function(result) {
			$("#contentCotizado").html(result.html);
		})
		.fail(function() {
			console.log("error");
		});
	},
	/*cargarInfoResumen: function(){
		var path = window.location.pathname;
		let array = path.split(`/`);
		if (array[2]=='resumen') {
			//codigo para resumen
			$("#resumen").html(array[2])
		}		
	}*/
}

jQuery(document).ready(function($) {
	procesoTienda.iniciarTienda();
});