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
      	"orden":[[0,'asc']],
      	"pageLength":25,
      	"dom":"lBfrtip",
      	"buttons":[{
      		"extend":"copy",
      		"className":"btn-sm"
      	},{
      		"extend":"print",
      		"className":"btn-sm"
      	}],
      	"responsive":1,
      	"autoFill":!0,
      	"colReader":!0,
      	"keys":!0,
      	"rowReorder":!0,
      	"select":!0
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
	}
}

jQuery(document).ready(function($) {
    procesosListaProductos.iniciarLista();
});