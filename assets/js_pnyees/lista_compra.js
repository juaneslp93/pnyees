procesosListaCompra = {
	iniciarLista: function(){
		var tableC = this.lista();
		this.procesar_compra(tableC);
	},
	lista: function(){
		var self = this;
      	var table = $('#lista-compra').DataTable({
			"processing":true,
			"serverside":true,
			"ajax":'../controller/ctr_lista_compra.php?entrada=lista_compra',
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
	continuar_procesar_compra: function(table, self, envio){
		$.ajax({
			url: '../controller/ctr_lista_compra.php',
			type: 'POST',
			dataType: 'json',
			data: $(self).serialize(),
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
			if(envio) window.open('generar-pdf-envio', '_blank');
			table.ajax.reload();
			document.getElementById("formProcesarCompra").reset();
		});
	},
	procesar_compra: function(table){
		$("#formProcesarCompra").submit(function(event){
			var self = this;
			event.preventDefault();
			$("button[type=submit]").attr("disabled", "disabled").html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
			if($("#anular-compra").prop("checked")==true){
				Swal.fire({
					title: '¿Está seguro de anular esta(s) compra(s)?',
					text: 'Este proceso es irreversible',
					showDenyButton: true,
					showCancelButton: false,
					confirmButtonText: 'Si, estoy seguro',
					denyButtonText: 'No, cancela esta acción',
				}).then((promised)=> {					
					if (promised.isConfirmed) {
						procesosListaCompra.continuar_procesar_compra(table, self, false);
					}else if(promised.isDenied){
						table.ajax.reload();
					}
				})
			}else if($("#aprobar-envio").prop("checked")==true){
				procesosListaCompra.continuar_procesar_compra(table, self, true);
				
			}			
		})
	}
}

jQuery(document).ready(function($) {
    procesosListaCompra.iniciarLista();
});