Detalle_orden_compra = {
    iniciarDetalleOrden: function(idOrden){
        var self = this;
        $.ajax({
            url: '../controller/ctr_lista_orden_compra.php',
            type: 'POST',
            dataType: 'json',
            data: {'entrada':'detalleOrdenCompra', 'idOrden':idOrden},
        }).done(function(result) {
            $("#contenidoOrden").html(result.html);
            self.procesar_orden();
        })
        .fail(function() {
            console.log("error");
        });
    },
    procesar_orden: function(){
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
				document.getElementById("formProcesarOrden").reset();
			});
		})
	}
}