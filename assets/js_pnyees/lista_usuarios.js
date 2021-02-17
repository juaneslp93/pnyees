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
      		self.eliminar_usuario(table, el);
      	})
      })
	},
	eliminar_usuario: function(table, el){
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
				  icon: 'success',
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

		
	}
}