Detalle_orden_compra = {
    iniciarDetalleOrden: function(idOrden){
        $.ajax({
            url: '../controller/ctr_lista_orden_compra.php',
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