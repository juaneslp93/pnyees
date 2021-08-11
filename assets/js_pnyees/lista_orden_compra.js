procesosListaOrdenesCompra = {
	iniciarLista: function(){
		var tableC = this.lista();
		this.procesar_orden(tableC);
	},
	lista: function(){
		var self = this;
      	var table = $('#lista-orden-compra').DataTable({
			"processing":true,
			"serverside":true,
			"ajax":'../controller/ctr_lista_orden_compra.php?entrada=lista_orden_compra',
			"order":[[7,'desc']],
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
      	
		$("#selectAllSwitch").on("click", function() {
			$(".all-switch").prop("checked", this.checked);
		});
			  
		// if all checkbox are selected, check the selectall checkbox and viceversa
		$(".all-switch").on("click", function() {
		if ($(".all-switch").length == $(".all-switch:checked").length) {
			$("#selectAllSwitch").prop("checked", true);
		} else {
			$("#selectAllSwitch").prop("checked", false);
		}
		});
		
      })
	},
	procesar_orden: function(table){
		$("#formProcesarOrden").submit(function(event){
			event.preventDefault();
			$("button[type=submit]").attr("disabled", "disabled").html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
			$.ajax({
				url: '../controller/ctr_lista_orden_compra.php',
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
			}).always(function() {
				$("button[type=submit]").removeAttr("disabled").html('Procesar selección');
				table.ajax.reload();
				document.getElementById("formProcesarOrden").reset();
			});
		})
	}
}

jQuery(document).ready(function($) {
    procesosListaOrdenesCompra.iniciarLista();
});