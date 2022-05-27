Detalle_orden_compra = {
    iniciarDetalleOrden: function(idOrden){
        var self = this;
        $.ajax({
            url: '../controller/ctr_lista_orden_compra_cliente.php',
            type: 'POST',
            dataType: 'json',
            data: {'entrada':'detalleOrdenCompra', 'idOrden':idOrden},
        }).done(function(result) {
            $("#contenidoOrden").html(result.html);
        })
        .fail(function() {
            console.log("error");
        });
    }
}