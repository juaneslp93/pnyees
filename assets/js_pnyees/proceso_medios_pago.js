procesoMediosPagos = {
	iniciar: function(){
		this.cagar_medios_pago();
		this.guardar_banco();
	},
	cagar_medios_pago: function(){
		$("#contenido").html('<i class="fa fa-spinner fa-spin"></i> Cargando...');
		$.ajax({
			url: '../controller/ctr_procesos_admin.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarMediosPagos'},
		})
		.done(function(result) {
			$("#contenido").html(result.html);
		})
		.fail(function() {
			console.log("error");
		});	
		
	},
	guardar_banco: function(){
		$("#FormRegistroBanco").submit(function(event){
			$("button").attr("disabled","disabled");
			event.preventDefault();
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: $(this).serialize(),
			})
			.done(function(result) {
				$("button").removeAttr("disabled");
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  text: result.mensaje
					})
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  text: result.mensaje
					})
				}
			})
			.fail(function() {
				console.log("error");
			});
			
		});
	},
	actDesBtn: function(input, elemento){
		if ($(elemento).attr('checked')=="checked") {
			$(elemento).removeAttr('checked').val('0');
		}else{
			$(elemento).attr({
				checked: 'checked',
				value: '1'
			});			
		}
		var valor = $(elemento).val();

		$.ajax({
			url: '../controller/ctr_procesos_admin.php',
			type: 'POST',
			dataType: 'json',
			data: {"entrada": 'actPasarela',"opcion":input, "valor":valor},
		})
		.done(function(result) {
			console.log(result);
		})
		.fail(function() {
			console.log("error");
		});		
	}
}

jQuery(document).ready(function($) {
	procesoMediosPagos.iniciar();
});