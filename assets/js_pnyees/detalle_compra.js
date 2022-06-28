Detalle_compra = {
    iniciarDetalleCompra: function(idCompra){
        var self = this;
        $.ajax({
            url: '../controller/ctr_lista_compra.php',
            type: 'POST',
            dataType: 'json',
            data: {'entrada':'detalleCompra', 'idCompra':idCompra},
        }).done(function(result) {
            $("#contenidoCompra").html(result.html);
            self.procesar_compra();
        })
        .fail(function() {
            console.log("error");
        });
    },
    procesar_compra: function(){
		$("#formProcesarCompra").submit(function(event){
			event.preventDefault();
			$("button[type=submit]").attr("disabled", "disabled").html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
			$.ajax({
				url: '../controller/ctr_lista_compra.php',
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
				document.getElementById("formProcesarCompra").reset();
			});
		})
	},
	generar_pdf: function(id){
		$.ajax({
			url: '../controller/ctr_lista_compra.php',
			type: 'POST',
			dataType: 'json',
			data: {"entrada":"generar_pdf", "id":id},
		})
		.fail(function() {
			console.log("error");
		}).always(function() {
			window.open('generar-pdf-envio', '_blank');
		});
	}
}