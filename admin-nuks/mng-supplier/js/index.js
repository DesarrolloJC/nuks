var tabla = $('#tblsuppliers').DataTable({
    ajax: "./controller/list.php",
    responsive: true,
    language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior"
        },
        oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    }
});
tabla.on("error.dt", function (e, settings, techNote, message, ajax) {
    console.log(e);
    console.log(settings);
    console.log(ajax);
});

$(document).on("click", ".pre_eraseFN", function () {
    var id = $(this).data('pro');
    Swal.fire({
        title: '¿Eliminar proveedor?',
        text: "El proveedor se eliminará completamente",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'controller/delete.php',
                type: 'GET',
                data: { "id": id },
                success: function (data) {
                    alerta('Eliminado!', 'Proveedor Eliminado', 'success', 'OK')
                    tabla.ajax.reload()
                }
            });
        }
    });
});

function editar(id) {
    let form = $("#EdSup form");
    $.ajax({
        url: 'controller/get.php',
        data: { 'id': id },
        type: 'GET',
        success: function (r) {

            form.find("input[name='id']").val(r.supplier_id);
            form.find("input[name='code']").val(r.supplier_code);
            form.find("input[name='name']").val(r.supplier_name);
            form.find("input[name='site']").val(r.supplier_website);
            form.find("input[name='api']").val(r.supplier_api);

            $('#EdSup').modal('show');
        },
        error: function (r) {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    });
}
