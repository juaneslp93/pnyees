configGeneral = {
    iniciarConfig: function () {
        this.cargaXdefecto();
        this.contenidoGeneral();
        this.datosFacturacion();
        this.rolesAdministracion();    
		this.crearRol();
		var tableC = this.listaRolesUsuarios();
		this.crearUsuario(tableC);
		// this.procesar_compra(tableC);  
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
    },
	crearRol: function () {
		$("#formNuevoRol").submit(function (e) { 
			e.preventDefault();
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: $(this).serialize(),
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  html: result.mensaje
					})
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}
			})
			.fail(function() {
				console.log("error");
			});	
		});
	},
	guardarRolUsuario: function (control, table) {
		
		$("#formAsignarRol").submit(function (e) { 
			e.preventDefault();			
			Swal.fire({
				icon: 'warning',
				title: '¡Atención!',
				text: "¿Está realmente seguro que desea asignar este rol a este usuario?",
				showDenyButton: true,
				showCancelButton: false,
				confirmButtonText: 'Si, estoy seguro',
				denyButtonText: 'No, cancela esta acción',
			}).then((promised)=> {
				if (promised.isConfirmed) {
					$.ajax({
						url: '../controller/ctr_procesos_admin.php',
						type: 'POST',
						dataType: 'json',
						data: $(this).serialize()+'&id='+control,
					})
					.done(function(result) {
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
					})
					.always(function(){
						table.ajax.reload();
						$("#contentTableModal").modal("hide");
						$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
						$('.modal-backdrop').remove();//eliminamos el backdrop del modal
						document.getElementById("formAsignarRol").reset();
					});
				}else if(promised.isDenied){
					table.ajax.reload();
					$("#contentTableModal").modal("hide");
					$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
					$('.modal-backdrop').remove();//eliminamos el backdrop del modal
					document.getElementById("formAsignarRol").reset();
				}
			})
		});
	},
	listaRolesUsuarios: function(){
		var self = this;
      	var table = $('#lista-roles-usuarios').DataTable({
			"processing":true,
			"serverside":true,
			"ajax":'../controller/ctr_procesos_admin.php?entrada=listaRolesUsuarios',
			"order":[[0,'asc']],
			"pageLength":25,
			"dom":"lBfrtip",
			"buttons":['excel', 'pdf', 'copy', 'print'],
			"responsive":true,
			"autoFill":false,
			"colReader":false,
			"keys":false,
			"rowReorder":false,
			"select":false
      });
	//   var n = document.createElement('script');
	// 	n.setAttribute('language', 'JavaScript');
	// 	n.setAttribute('src', 'https://debug.datatables.net/debug.js');
	// 	document.body.appendChild(n);

      return table.on('draw', function(){
      	
		$(".asignarRol").on("click", function(e) {
			var self = this;
			e.preventDefault();
			var control = $(this).attr("data-control");
			$("#tableActionContent").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-primary m-5 " role="status"><span class="sr-only">Cargando...</span></div></div>');
			$("#contentTableModal").modal("show");
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: {"entrada":'cargarRoles', "data-control":control}	
			})
			.done(function(result) {
				$("#tableActionContent").html(result.html);
				configGeneral.guardarRolUsuario(control, table);
				// table.ajax.reload();
				// $("#nuevoUsuarioModal").modal("hide");
				// $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
				// $('.modal-backdrop').remove();//eliminamos el backdrop del modal
				// document.getElementById("formNuevoUsuario").reset();
			})
			.fail(function() {
				console.log("error");
			});
		});
		 
		$(".editarUsuario").on("click", function(e) {
			e.preventDefault();
			var control = $(this).attr("data-control");
			$("#tableActionContent").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-primary m-5 " role="status"><span class="sr-only">Cargando...</span></div></div>');
			$("#contentTableModal").modal("show");
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: {"entrada":'editarUsuario', "data-control":control}	
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  html: result.mensaje
					})
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}
				table.ajax.reload();
				// $("#nuevoUsuarioModal").modal("hide");
				// $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
				// $('.modal-backdrop').remove();//eliminamos el backdrop del modal
				// document.getElementById("formNuevoUsuario").reset();
			})
			.fail(function() {
				console.log("error");
			});
		});
		
		$(".eliminarUsuario").on("click", function(e) {
			e.preventDefault();
			var control = $(this).attr("data-control");
			$("#tableActionContent").html('<div class="d-flex justify-content-center"><div class="spinner-grow text-primary m-5 " role="status"><span class="sr-only">Cargando...</span></div></div>');
			$("#contentTableModal").modal("show");
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: {"entrada":'eliminarUsuario', "data-control":control}	
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  html: result.mensaje
					})
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}
				table.ajax.reload();
				// $("#nuevoUsuarioModal").modal("hide");
				// $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
				// $('.modal-backdrop').remove();//eliminamos el backdrop del modal
				// document.getElementById("formNuevoUsuario").reset();
			})
			.fail(function() {
				console.log("error");
			});
		});		
      });
	},
	crearUsuario: function (table) {
		$("#formNuevoUsuario").submit(function (e) { 
			e.preventDefault();
			$.ajax({
				url: '../controller/ctr_procesos_admin.php',
				type: 'POST',
				dataType: 'json',
				data: $(this).serialize(),
			})
			.done(function(result) {
				if (result.continue) {
					Swal.fire({
					  icon: 'success',
					  title: '¡Proceso exitoso!',
					  html: result.mensaje
					})
				}else{
					Swal.fire({
					  icon: 'warning',
					  title: '¡Proceso detenido!',
					  html: result.mensaje
					})
				}
				table.ajax.reload();
				$("#nuevoUsuarioModal").modal("hide");
				$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
				$('.modal-backdrop').remove();//eliminamos el backdrop del modal
				document.getElementById("formNuevoUsuario").reset();
			})
			.fail(function() {
				console.log("error");
			});	
		});
	}
}

jQuery(document).ready(function($) {
    configGeneral.iniciarConfig();
});