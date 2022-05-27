procesosListaOrdenesCompra = {
	iniciarLista: function(){
		this.lista();
	},
	lista: function(){
		var self = this;
      	var table = $('#lista-orden-compra').DataTable({
			"processing":true,
			"serverside":true,
			"ajax":'../controller/ctr_lista_orden_compra_cliente.php?entrada=lista_orden_compra',
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
	}
}

jQuery(document).ready(function($) {
    procesosListaOrdenesCompra.iniciarLista();
});