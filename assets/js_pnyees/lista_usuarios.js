procesosListaUsuarios = {
	iniciarLista: function(){
		this.lista();
	},
	lista: function(){
		var self = this;
      	var table = $('#lista-usuarios').DataTable({
      	"processing":true,
      	"serverside":true,
      	"ajax":'../controller/ctr_lista_usuarios.php?entrada=lista_usuarios',
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

      table.on('draw', function(){
      	$(".eliminar_usuario").on('click', function(){
      		var el = $(this).attr('data-control');
      		self.eliminar_usuario(table, el, this);      		
      	})
      	$(".usuarioEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarUsuario(table, el, this);      		
      	})
      	$(".nombreEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarNombre(table, el, this);      		
      	})
      	$(".apellidoEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarApellido(table, el, this);      		
      	})
      	$(".correoEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarCorreo(table, el, this);      		
      	})
      	$(".telefonoEditar").on('dblclick', function(){
      		var el = $(this).attr('data-control');
      		self.editarTelefono(table, el, this);      		
      	})
      })
	},
	eliminar_usuario: function(table, el, ele){
		$.ajax({
			url: '../controller/ctr_lista_usuarios.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'eliminar_usuario', 'id': el},
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
	},
	editarUsuario: function(table, el, ele){
		$("#U"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
		$(ele).html('');
		$('input[name='+el+']').on('change',function(event){
			event.preventDefault();
			var newVal = $(this).val();
			$.ajax({
				url: '../controller/ctr_lista_usuarios.php',
				type: 'POST',
				dataType: 'json',
				data: {'entrada': 'editar_usuario', 'valor':newVal, "caso":'usuario', 'id':el},
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
	},
	editarNombre: function(table, el, ele){
		$("#N"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
		$(ele).html('');
		$('input[name='+el+']').on('change',function(event){
			event.preventDefault();
			var newVal = $(this).val();
			$.ajax({
				url: '../controller/ctr_lista_usuarios.php',
				type: 'POST',
				dataType: 'json',
				data: {'entrada': 'editar_usuario', 'valor':newVal, "caso":'nombre', 'id':el},
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
	},
	editarApellido: function(table, el, ele){
		$("#A"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
		$(ele).html('');
		$('input[name='+el+']').on('change',function(event){
			event.preventDefault();
			var newVal = $(this).val();
			$.ajax({
				url: '../controller/ctr_lista_usuarios.php',
				type: 'POST',
				dataType: 'json',
				data: {'entrada': 'editar_usuario', 'valor':newVal, "caso":'apellido', 'id':el},
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
	},
	editarCorreo: function(table, el, ele){
		$("#C"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
		$(ele).html('');
		$('input[name='+el+']').on('change',function(event){
			event.preventDefault();
			var newVal = $(this).val();
			$.ajax({
				url: '../controller/ctr_lista_usuarios.php',
				type: 'POST',
				dataType: 'json',
				data: {'entrada': 'editar_usuario', 'valor':newVal, "caso":'correo', 'id':el},
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
	},
	editarTelefono: function(table, el, ele){
		$("#T"+el).html('<input name="'+el+'" class="form-control" value="'+$(ele).html().trim()+'"/>')
		$(ele).html('');
		$('input[name='+el+']').on('change',function(event){
			event.preventDefault();
			var newVal = $(this).val();
			$.ajax({
				url: '../controller/ctr_lista_usuarios.php',
				type: 'POST',
				dataType: 'json',
				data: {'entrada': 'editar_usuario', 'valor':newVal, "caso":'telefono', 'id':el},
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
	}
}

jQuery(document).ready(function($) {
    procesosListaUsuarios.iniciarLista();
});