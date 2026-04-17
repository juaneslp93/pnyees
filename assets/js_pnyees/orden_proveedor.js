var OrdenProveedor = {
    iniciarLista: function () {
        var self = this;
        var table = $('#lista-ordenes-proveedor').DataTable({
            processing: true,
            serverSide: true,
            ajax: '../controller/ctr_ordenes_proveedor.php?entrada=lista_ordenes_proveedor',
            order: [[0, 'desc']],
            pageLength: 25,
            dom: 'lBfrtip',
            buttons: ['excel', 'pdf', 'copy', 'print'],
            responsive: true
        });

        $('#formNuevaOrden').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.post('../controller/ctr_ordenes_proveedor.php', data, function (res) {
                if (res.continue) {
                    window.location.href = res.redirect;
                } else {
                    Swal.fire({ icon: 'warning', title: 'Error', text: res.mensaje });
                }
            }, 'json').fail(function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de comunicación' });
            }).always(function () {
                $('#nuevaOrdenModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
        });
    },

    iniciarDetalle: function () {
        // Recepcionar orden
        $('#btnRecepcionar').on('click', function () {
            var idOrden = $(this).data('orden');
            Swal.fire({
                title: '¿Confirmar recepción de mercancía?',
                text: 'Se incrementará el stock de todos los ítems de esta orden.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, recepcionar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.post('../controller/ctr_ordenes_proveedor.php', {
                        entrada: 'recepcionar_orden',
                        id_orden: idOrden
                    }, function (res) {
                        if (res.continue) {
                            Swal.fire({ icon: 'success', title: '¡Recepción registrada!', text: res.mensaje })
                                .then(function () { location.reload(); });
                        } else {
                            Swal.fire({ icon: 'warning', title: 'Error', text: res.mensaje });
                        }
                    }, 'json');
                }
            });
        });

        // Cancelar orden
        $('#btnCancelar').on('click', function () {
            var idOrden = $(this).data('orden');
            Swal.fire({
                title: '¿Cancelar esta orden?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar orden',
                cancelButtonText: 'No'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.post('../controller/ctr_ordenes_proveedor.php', {
                        entrada: 'cancelar_orden',
                        id_orden: idOrden
                    }, function (res) {
                        if (res.continue) {
                            Swal.fire({ icon: 'success', title: 'Orden cancelada', text: res.mensaje })
                                .then(function () { location.reload(); });
                        } else {
                            Swal.fire({ icon: 'warning', title: 'Error', text: res.mensaje });
                        }
                    }, 'json');
                }
            });
        });

        // Agregar ítem
        $('#formAgregarItem').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post('../controller/ctr_ordenes_proveedor.php', formData, function (res) {
                if (res.continue) {
                    Swal.fire({ icon: 'success', title: '¡Ítem agregado!', text: res.mensaje })
                        .then(function () { location.reload(); });
                } else {
                    Swal.fire({ icon: 'warning', title: 'Error', text: res.mensaje });
                }
            }, 'json').fail(function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Error de comunicación' });
            }).always(function () {
                $('#agregarItemModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
        });

        // Eliminar ítem
        $('body').on('click', '.btn-eliminar-item', function () {
            var idItem  = $(this).data('item');
            var idOrden = $(this).data('orden');
            Swal.fire({
                title: '¿Eliminar este ítem?',
                showDenyButton: true,
                confirmButtonText: 'Sí, eliminar',
                denyButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.post('../controller/ctr_ordenes_proveedor.php', {
                        entrada: 'eliminar_item_orden',
                        id_item: idItem,
                        id_orden: idOrden
                    }, function (res) {
                        if (res.continue) {
                            location.reload();
                        } else {
                            Swal.fire({ icon: 'warning', title: 'Error', text: res.mensaje });
                        }
                    }, 'json');
                }
            });
        });
    }
};

jQuery(document).ready(function () {
    if ($('#lista-ordenes-proveedor').length) {
        OrdenProveedor.iniciarLista();
    }
    if (typeof idOrdenActual !== 'undefined') {
        OrdenProveedor.iniciarDetalle();
    }
});
