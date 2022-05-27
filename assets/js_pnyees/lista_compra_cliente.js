procesosListaCompra = {
	iniciarLista: function(){
		this.lista();
	},
	lista: function(){
		$('#lista-compra').DataTable({
			"processing":true,
			"serverside":true,
			"ajax":'../controller/ctr_lista_compra_cliente.php?entrada=lista_compra',
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
	}
}

jQuery(document).ready(function($) {
    procesosListaCompra.iniciarLista();
});