procesoPagos = {
	iniciar: function(){
		this.cargar_datos_facturacion();
		this.cargar_botones_de_pago();

	},
	cargar_botones_de_pago: function(){
		var self = this;
		$("#botones").html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
		$.ajax({
			url: 'controller/ctr_catalogo.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'botonesPasarela'},
		})
		.done(function(result) {
			$("#botones").html(result.html);
			self.cargarDatosBancarios();
			self.finalizarDeposito();
		})
		.fail(function() {
			console.log("error");
		});	
		
	},
	cargar_datos_facturacion: function(nuevaDir='false'){
		$.ajax({
			url: 'controller/ctr_pagos.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarDatosFacturacion', 'nuevaDirSel':nuevaDir},
		})
		.done(function(result) {

			if (result.continue) {				
				$("#contenidoDir").html(result.html);
				$("#direccionModal").modal({backdrop: 'static', keyboard: false, show: true});
				$("#formDireccionSelect").submit(function(event) {
					/* Act on the event */
					event.preventDefault();
					$.ajax({
						url: 'controller/ctr_pagos.php',
						type: 'POST',
						dataType: 'json',
						data: $(this).serialize(),
					})
					.done(function(result) {
						if (result.continue) {
							$("#direccionModal").modal("hide");
							$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							document.getElementById("formDireccionSelect").reset();
							$("#direEdit").html('<button class="btn btn-warning btn-xs" onclick="procesoPagos.editDirSel();"><i class="fa fa-edit"></i> Seleccionar otra dirección</button>');
						}
					})
					.fail(function() {
						console.log("error");
					});
					
				});
			}else{
				$("#direEdit").html('<button class="btn btn-warning btn-xs" onclick="procesoPagos.editDirSel();"><i class="fa fa-edit"></i> Seleccionar otra dirección</button>');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},
	cargarDatosBancarios: function(){
		$("#bancoBtn").on('click', function(event) {
			event.preventDefault();
			$("#datosBanco").modal('show');
		});
	},
	finalizarDeposito:function() {
		$("#FormRegistroOrdenCompra").submit(function(event) {
			/* Act on the event */
			event.preventDefault();
			$("button[type=submit]").attr("disabled", "disabled").html('<i class="fa fa-spinner fa-spin"></i>');
			$.ajax({
				url: 'controller/ctr_pagos.php',
				type: 'POST',
				dataType: 'json',
				data:$(this).serialize(),
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso completado!',
					  html: result.mensaje
					}).then((promised)=> {
						if (promised.isConfirmed) {
							window.location.reload();
						}
					});
				}else{
					Swal.fire({
						icon: 'warning',
						title: '¡Proceso detenido!',
						html: result.mensaje
					  });
				}
			})
			.fail(function() {
				console.log("error");
			});
			
		});
	},
	editDirSel:function () {
		// body... 
		
		var self = this;
		$.ajax({
			url: 'controller/ctr_pagos.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cambiarDirSel'},
		})
		.done(function() {
			//
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			self.cargar_datos_facturacion();
		});
		
		
	}
}

jQuery(document).ready(function($) {
	procesoPagos.iniciar();
});