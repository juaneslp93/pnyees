Detalle_compra = {
    iniciarDetalleCompra: function(idCompra){
        var self = this;
        $.ajax({
            url: '../controller/ctr_lista_compra_cliente.php',
            type: 'POST',
            dataType: 'json',
            data: {'entrada':'detalleCompra', 'idCompra':idCompra},
        }).done(function(result) {
            $("#contenidoCompra").html(result.html);
        })
        .fail(function() {
            console.log("error");
        });
    },
	generar_pdf: function(id){
		$.ajax({
			url: '../controller/ctr_lista_compra_cliente.php',
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