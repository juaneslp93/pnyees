configGeneral = {
    iniciarConfig: function () {
        this.cargaXdefecto();
        this.contenidoGeneral();
        this.datosFacturacion();
        this.rolesAdministracion();
    },
    cargaXdefecto: function () {
        $.ajax({
            url: '../controller/ctr_procesos_admin.php',
            type: 'POST',
            dataType: 'json',
            data: {'entrada': 'cargarConfigGeneral'},
            success: function (response) {
                $("#contenidoSistema").html(response.html);
                $("#contenidoFacturacion").html("");
                $("#contenidoRoles").html("");
            }
        });
    },
    contenidoGeneral: function () {
        $("#sistema-tab").click(function (e) { 
            e.preventDefault();
            $.ajax({
                url: '../controller/ctr_procesos_admin.php',
		    	type: 'POST',
		    	dataType: 'json',
		    	data: {'entrada': 'cargarConfigGeneral'},
                success: function (response) {
                    $("#contenidoSistema").html(response.html);
                    $("#contenidoFacturacion").html("");
                    $("#contenidoRoles").html("");
                }
            });
        });
    },
    datosFacturacion: function () {
        $("#facturacion-tab").click(function (e) { 
            e.preventDefault();
            $.ajax({
                url: '../controller/ctr_procesos_admin.php',
		    	type: 'POST',
		    	dataType: 'json',
		    	data: {'entrada': 'cargarTitulacionEmpresarial'},
                success: function (response) {
                    $("#contenidoSistema").html("");
                    $("#contenidoFacturacion").html(response.html);
                    $("#contenidoRoles").html("");
                }
            });
        });
    },
    rolesAdministracion: function () {
        $("#roles-tab").click(function (e) { 
            e.preventDefault();
            $.ajax({
                url: '../controller/ctr_procesos_admin.php',
		    	type: 'POST',
		    	dataType: 'json',
		    	data: {'entrada': 'cargarRolesAdministracion'},
                success: function (response) {
                    $("#contenidoSistema").html("");
                    $("#contenidoFacturacion").html("");
                    $("#contenidoRoles").html(response.html);
                }
            });
        });        
    },
    modificarEstadoRol:function (input, elemento) {
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
			data: {"entrada": 'actRoles',"opcion":input, "valor":valor},
		})
		.done(function(result) {
			console.log(result);
		})
		.fail(function() {
			console.log("error");
		});	
    },
    modificarPermisoVer:function (input, elemento) {
        if ($(elemento).attr('checked')=="checked") {
			$(elemento).removeAttr('checked').val('0');
		}else{
			$(elemento).attr({
				checked: 'checked',
				value: '1'
			});			
		}
		var valor = $(elemento).val();
        var dataC = $(elemento).attr("data-control");

		$.ajax({
			url: '../controller/ctr_procesos_admin.php',
			type: 'POST',
			dataType: 'json',
			data: {"entrada": 'actPermiso',"opcion":input, "valor":valor, "campo":dataC},
		})
		.done(function(result) {
			console.log(result);
		})
		.fail(function() {
			console.log("error");
		});	
    },
    modificarPermisoCrear:function (input, elemento) {
        if ($(elemento).attr('checked')=="checked") {
			$(elemento).removeAttr('checked').val('0');
		}else{
			$(elemento).attr({
				checked: 'checked',
				value: '1'
			});			
		}
		var valor = $(elemento).val();
        var dataC = $(elemento).attr("data-control");

		$.ajax({
			url: '../controller/ctr_procesos_admin.php',
			type: 'POST',
			dataType: 'json',
			data: {"entrada": 'actPermiso',"opcion":input, "valor":valor, "campo":dataC},
		})
		.done(function(result) {
			console.log(result);
		})
		.fail(function() {
			console.log("error");
		});	
    },
    modificarPermisoEditar:function (input, elemento) {
        if ($(elemento).attr('checked')=="checked") {
			$(elemento).removeAttr('checked').val('0');
		}else{
			$(elemento).attr({
				checked: 'checked',
				value: '1'
			});			
		}
		var valor = $(elemento).val();
        var dataC = $(elemento).attr("data-control");

		$.ajax({
			url: '../controller/ctr_procesos_admin.php',
			type: 'POST',
			dataType: 'json',
			data: {"entrada": 'actPermiso',"opcion":input, "valor":valor, "campo":dataC},
		})
		.done(function(result) {
			console.log(result);
		})
		.fail(function() {
			console.log("error");
		});	
    },
    modificarPermisoEliminar:function (input, elemento) {
        if ($(elemento).attr('checked')=="checked") {
			$(elemento).removeAttr('checked').val('0');
		}else{
			$(elemento).attr({
				checked: 'checked',
				value: '1'
			});			
		}
		var valor = $(elemento).val();
        var dataC = $(elemento).attr("data-control");

		$.ajax({
			url: '../controller/ctr_procesos_admin.php',
			type: 'POST',
			dataType: 'json',
			data: {"entrada": 'actPermiso',"opcion":input, "valor":valor, "campo":dataC},
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
    configGeneral.iniciarConfig();
});