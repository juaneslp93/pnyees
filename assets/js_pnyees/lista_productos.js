procesosListaProductos = {
	iniciarLista: function(){
		var tableC = this.lista();
      	this.crearProducto(tableC);      	
	},
	lista: function(){
		var self = this;
      	var table = $('#lista-productos').DataTable({
      	"processing":true,
      	"serverside":true,
      	"ajax":'../controller/ctr_lista_productos.php?entrada=lista_productos',
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

      return table.on('draw', function(){      	
      	$(".eliminar_producto").on('click', function(){
      		var el = $(this).attr('data-control');
      		self.eliminar_Producto(table, el, this);      		
      	})
      	$(".productoEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarProducto(table, el, this);      		
      	})
      	$(".descripcionEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarDescripcion(table, el, this);      		
      	})
      	$(".precioEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarPrecio(table, el, this);      		
      	})
      	$(".impuestoEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarImpuesto(table, el, this);      		
      	})
      	$(".imagenEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarImagen(table, el, this);      		
      	})
      })
	},
	crearProducto: function(table){	
		$("#FormRegistroProducto").submit(function(event) {	
			event.preventDefault();
			var formData = new FormData(document.getElementById("FormRegistroProducto"));
			formData.append("dato", "valor");

			$.ajax({
				url: '../controller/ctr_lista_Productos.php',
				type: 'POST',
				dataType: 'json',
				data: formData,
				cache:false,
				contentType: false,
				processData:false
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
			})
			.always(function() {
				$("#newProductoModal").modal('hide');//cerramos el modal
				$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
					$('.modal-backdrop').remove();//eliminamos el backdrop del modal
				table.ajax.reload();
				document.getElementById("FormRegistroProducto").reset();
			});
		})
	},
	eliminar_Producto: function(table, el, ele){
		Swal.fire({
			title: '¿Desea modificar este campo?',
		  	showDenyButton: true,
		  	showCancelButton: false,
		  	confirmButtonText: 'Si, deseo hacerlo',
		  	denyButtonText: 'No, cancela esta acción',
		}).then((promised)=> {
			if (promised.isConfirmed) {
				$.ajax({
					url: '../controller/ctr_lista_productos.php',
					type: 'POST',
					dataType: 'json',
					data: {'entrada': 'eliminar_producto', 'id': el},
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
				});
			}else if(promised.isDenied){
				table.ajax.reload();
			}
		})		
	},
	editarProducto: function(table, el, ele){
		Swal.fire({
			title: '¿Desea modificar este campo?',
		  	showDenyButton: true,
		  	showCancelButton: false,
		  	confirmButtonText: 'Si, deseo hacerlo',
		  	denyButtonText: 'No, cancela esta acción',
		}).then((promised)=> {
			
			if (promised.isConfirmed) {
				$("#P"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
				$(ele).html('');
				$('input[name='+el+']').on('change',function(event){
					event.preventDefault();
					var newVal = $(this).val();
					$.ajax({
						url: '../controller/ctr_lista_Productos.php',
						type: 'POST',
						dataType: 'json',
						data: {'entrada': 'editar_producto', 'valor':newVal, "caso":'nombre', 'id':el},
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
					.always(function() {
						table.ajax.reload();
					});			
				})	 

			}else if(promised.isDenied){
				table.ajax.reload();
			}
		})			
	},
	editarDescripcion: function(table, el, ele){
		Swal.fire({
			title: '¿Desea modificar este campo?',
		  	showDenyButton: true,
		  	showCancelButton: false,
		  	confirmButtonText: 'Si, deseo hacerlo',
		  	denyButtonText: 'No, cancela esta acción',
		}).then((promised)=> {
			
			if (promised.isConfirmed) {
				$("#D"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
				$(ele).html('');
				$('input[name='+el+']').on('change',function(event){
					event.preventDefault();
					var newVal = $(this).val();
					$.ajax({
						url: '../controller/ctr_lista_Productos.php',
						type: 'POST',
						dataType: 'json',
						data: {'entrada': 'editar_producto', 'valor':newVal, "caso":'descripcion', 'id':el},
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
					.always(function() {
						table.ajax.reload();
					});			
				})
			}else if(promised.isDenied){
				table.ajax.reload();
			}
		})
	},
	editarPrecio: function(table, el, ele){
		Swal.fire({
			title: '¿Desea modificar este campo?',
		  	showDenyButton: true,
		  	showCancelButton: false,
		  	confirmButtonText: 'Si, deseo hacerlo',
		  	denyButtonText: 'No, cancela esta acción',
		}).then((promised)=> {
			
			if (promised.isConfirmed) {
				$("#Pr"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
				$(ele).html('');
				$('input[name='+el+']').on('change',function(event){
					event.preventDefault();
					var newVal = $(this).val();
					$.ajax({
						url: '../controller/ctr_lista_Productos.php',
						type: 'POST',
						dataType: 'json',
						data: {'entrada': 'editar_producto', 'valor':newVal, "caso":'precio', 'id':el},
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
					.always(function() {
						table.ajax.reload();
					});			
				})
			}else if(promised.isDenied){
				table.ajax.reload();
			}
		})
	},
	editarImpuesto: function(table, el, ele){
		Swal.fire({
			title: '¿Desea modificar este campo?',
		  	showDenyButton: true,
		  	showCancelButton: false,
		  	confirmButtonText: 'Si, deseo hacerlo',
		  	denyButtonText: 'No, cancela esta acción',
		}).then((promised)=> {
			
			if (promised.isConfirmed) {
				$("#I"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
				$(ele).html('');
				$('input[name='+el+']').on('change',function(event){
					event.preventDefault();
					var newVal = $(this).val();
					$.ajax({
						url: '../controller/ctr_lista_Productos.php',
						type: 'POST',
						dataType: 'json',
						data: {'entrada': 'editar_producto', 'valor':newVal, "caso":'impuesto', 'id':el},
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
					.always(function() {
						table.ajax.reload();
					});			
				})	
			}else if(promised.isDenied){
				table.ajax.reload();
			}
		})
	},
	editarImagen: function(table, el, ele){
		Swal.fire({
			title: '¿Desea modificar este campo?',
		  	showDenyButton: true,
		  	showCancelButton: false,
		  	confirmButtonText: 'Si, deseo hacerlo',
		  	denyButtonText: 'No, cancela esta acción',
		}).then((promised)=> {
			
			if (promised.isConfirmed) {			
				$(ele).html('');
				$("#M"+el).html('<form id="MForm'+el+'" enctype="multipart/form-data" method="post">\
					<input type="hidden" value="editar_producto" name="entrada"/>\
					<input type="hidden" value="url_imagen" name="caso"/>\
					<input type="hidden" value="'+el+'" name="id"/>\
					<input name="'+el+'" type="file" class="form-control" value="'+$(ele).html().trim()+'" accept="image/*"/>\
					</form>');
				$('input[name='+el+']').on('change',function(event){
					var newVal = $(this).val();
					$("#MForm"+el).append('<input type="hidden" value="'+newVal+'" name="valor"/>')
					event.preventDefault();
					console.log(document.getElementById("M"+el));
					console.log($("#M"+el));
					var formData = new FormData(document.getElementById("MForm"+el));
					formData.append("dato", "valor");
					$.ajax({
						url: '../controller/ctr_lista_Productos.php',
						type: 'POST',
						dataType: 'json',
						data: formData,
						cache:false,
						contentType: false,
						processData:false
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
					.always(function() {
						table.ajax.reload();
					});			
				})	
			}else if(promised.isDenied){
				table.ajax.reload();
			}
		})
	},
	cargarCampos: function(ele){
		var maxInputs = 20;
		var contenedor = $("#newItem"+ele);
		var botonPlus = $("#botonPlus"+ele);
		var x = $("#elementos_agregados"+ele+" div[id]").length+1;
		var fieldCount = x-1;

		$(botonPlus).on('click', function(){
			if (x<=maxInputs) {
				fieldCount++;
				$(contenedor).append('\
					<div id="'+x+'" class="row col-lg-12">\
						<div class="col-sm-3 mb-3 mb-sm-0">\
	                        <input type="number" class="form-control form-control-user" name="min[]" placeholder="Mínimo" required>\
	                    </div>\
	                    <div class="col-sm-3 mb-3 mb-sm-0">\
	                        <input type="number" class="form-control form-control-user" name="max[]" placeholder="Máximo" required>\
	                    </div>\
	                    <div class="col-sm-3 mb-3 mb-sm-0">\
	                        <input type="number" step="any" class="form-control form-control-user" name="descuento[]" placeholder="Descuento" required>\
	                    </div>\
	                    <div class="col-sm-3 mb-3 mb-sm-0">\
	                        <a class="eliminarItem btn btn-danger" data-control="'+x+'"><i class="fa fa-trash"></i></a>\
	                    </div>\
	                </div>\
                ');
                x++;
			}
			return false;
		});
		$("body").on("click", ".eliminarItem", function(){
			var a = $(this).attr('data-control');
			$("#"+a).remove();
			return false;
		})
	},
	cargarElementosAgregados: function(ele){
		var self = this;
		$("#desFormMdl").html('Cargando... <span class="fa fa-spinner fa-spin"></span>')
		$.ajax({
			url: '../controller/ctr_lista_productos.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarDescuentos', 'id': ele},
		})
		.done(function(result) {
			var formDesc = '<form class="user" id="FormRegistroProductoDescuento" enctype="multipart/form-data" method="post">\
                    <div class="form-group row">\
                        <div class="col-sm-3 mb-3 mb-sm-0">\
                            <label for="" class="label control-label">mínimo</label>\
                        </div>\
                        <div class="col-sm-3 mb-3 mb-sm-0">\
                            <label for="" class="label control-label">máximo</label>\
                        </div>\
                        <div class="col-sm-3 mb-3 mb-sm-0">\
                            <label for="" class="label control-label">Descuento</label>\
                        </div>\
                        <div class="col-sm-3 mb-3 mb-sm-0">\
                            <label for="" class="label control-label"> <button type="button" class="btn btn-success" id="botonPlus'+ele+'"><i class=" fa fa-plus"></i></button></label>\
                        </div>\
                    </div>\
                    <div class="form-group row" id="elementos_agregados'+ele+'">\
                    '+result.elementos_agregados+'\
                    </div>\
                    <div class="form-group row" id="newItem'+ele+'"></div>\
                    <hr>\
                    <input type="hidden" name="entrada" value="crear_descuento">\
                    <input type="hidden" name="id" value="'+ele+'">\
                    <button type="submit" class="btn btn-primary btn-user btn-block">\
                        Registrar descuento\
                    </button>\
                    <hr>\
                </form>';
            $("#desFormMdl").html(formDesc);
			self.cargarCampos(ele);
			self.guardarDescuento();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			$("#descuentosModal").modal("show");
						
		});
	},
	guardarDescuento: function(){
		$("#FormRegistroProductoDescuento").submit(function(event) {
			/* Act on the event */
			event.preventDefault();
			$.ajax({
				url: '../controller/ctr_lista_productos.php',
				type: 'POST',
				dataType: 'json',
				data: $(this).serialize()
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
			})
			.always(function() {
				$("#descuentosModal").modal("hide");
				$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
				$('.modal-backdrop').remove();//eliminamos el backdrop del modal
				document.getElementById("FormRegistroProducto").reset();
			});
		});
	}
}

jQuery(document).ready(function($) {
    procesosListaProductos.iniciarLista();
});