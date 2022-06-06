detallesUsuarios = {
	iniciar: function(){
		this.cargarInfo();
	},
	cargarInfo: function(){
		var id = $("#info-usuario").attr('data-control');
		$.ajax({
			url: '../controller/ctr_procesos_user.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarInfoDetallesUsuarios', 'id':id},
		})
		.done(function(result) {
			$("#info-usuario").html(result.html);
		})
		.fail(function() {
			console.log("error");
		});
	}
}
jQuery(document).ready(function($) {	
	detallesUsuarios.iniciar();
});