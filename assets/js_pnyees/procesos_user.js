/*procesosDashboard = {
	dashboard: function(){
		this.iniciar();
	},
	iniciar: function () {
		$.ajax({
			url: '../controller/ctr_inicio.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'datosInicio'},
		})
		.done(function(result) {
			$("#total-usuarios").html(result.datos.total_usuarios);
			$("#total-compras").html(result.datos.total_compra);
			$("#progreso-envios").html(result.datos.envios_realizados);
			$("#total-productos").html(result.datos.total_productos);
		})
		.fail(function() {
			console.log("error");
		});
		
	}
}

procesosGenerales = {
	invocar:function () {
		this.iniciar_dashboard();
		this.iniciar_nabvar();
	},
	iniciar_nabvar: function(){
		$.ajax({
			url: '../controller/ctr_inicio.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'notificaciones'},
		})
		.done(function(result) {
			$("#notif-bel").html(result.datos.notif_bel);
			$("#notif-content").html(result.datos.notif_content);
			$("#notif-mensaje").html(result.datos.notif_mensaje);
		})
		.fail(function() {
			console.log("error");
		});
		
	},
	iniciar_dashboard:function () {
		if (this.filename()==="inicio"||this.filename()==="inicio#") {
			procesosDashboard.dashboard();
		}
	},
	filename: function(){
		var rutaAbsoluta = self.location.href;   
		var posicionUltimaBarra = rutaAbsoluta.lastIndexOf("/");
		var rutaRelativa = rutaAbsoluta.substring( posicionUltimaBarra + "/".length , rutaAbsoluta.length );
		return rutaRelativa;  
	}
}

jQuery(document).ready(function($) {
	procesosGenerales.invocar();
});
*/