procesosDashboard = {
	dashboard: function(){
		this.iniciar();
	},
	iniciar: function () {
		$.ajax({
			url: '../controller/ctr_procesos_user.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'datosInicio'},
		})
		.done(function(result) {
			$("#total-cotizaciones").html(result.datos.total_cotizaciones);
			$("#total-compras").html(result.datos.total_compra);
			$("#progreso-envios").html(result.datos.envios_realizados);
			$("#total-envios").html(result.datos.total_envios);
		})
		.fail(function() {
			console.log("error");
		});
		
	}
}

procesosGenerales = {
	invocar:function () {
		this.iniciar_dashboard();
		// this.iniciar_nabvar();
	},
	iniciar_nabvar: function(){
		$.ajax({
			url: '../controller/ctr_procesos_user.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'notificaciones'},
		})
		.done(function(result) {
			$("#notif-bel").html(result.datos.bell);
			$("#notif-content").html(result.datos.notif_content);
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