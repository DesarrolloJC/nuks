var tabla = $('#tblusers').DataTable({
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

$(document).on("click", ".pre_eraseFN", function () {
    var id = $(this).data('pro');
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: "El usuario se eliminará completamente",
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
                data: {"id": id},
                success: function (data) {
                    alerta('Eliminado!', 'Ususario Eliminado', 'success', 'OK')
                    table.ajax.reload()
                }
            });
        }
    });
});

$(document).on("click", ".addContact", function () {
    //alerta('Exito!', 'Se intenta agregar contacto', 'info', 'Aceptar');
    let form = $("#NwUs form")
    $.ajax({
        url: 'controller/getRol.php',
        type: 'GET',
        success: function (r) {
            form.find("select[name='role']").html(r);
        },
        error: function () {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    })
});

function editar(id) {
    let form = $("#EdUs form");
    $.ajax({
        url: 'controller/get.php',
        data: {'id': id},
        type: 'GET',
        success: function (r) {
            console.log(r)
            form.find("input[name='id']").val(r.user_id);
            form.find("input[name='name']").val(r.user_name);
            form.find("input[name='apellido']").val(r.user_lastname);
            form.find("input[name='sesion']").val(r.user_email);
            // form.find("input[name='contra']").val(r.user_pass);
            // Comentado para que el usuario reingrese su contraseña
            // form.find("select[name='role']").val(r.user_role);
            form.find("select[name='role']").html(r.user_role);
            $('#EdUs').modal('show');
        },
        error: function () {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    });
}
