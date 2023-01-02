var tabla = $('#tblSEO').DataTable({
    ajax: "./controller/list.php",
    responsive: true,
    width: "auto",
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

function editar(id) {
    let form = $("#EdSEO form");
    $.ajax({
        url: 'controller/get.php',
        data: {'id': id},
        type: 'GET',
        success: function (r) {
            form.find("input[name='id']").val(r.seo_id);
            form.find("textarea[name='keywords']").val(r.seo_keywords);
            form.find("textarea[name='description']").val(r.seo_description);
            $('#EdSEO').modal('show');
        },
        error: function () {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    });
}

$('#sitemapGen').on('click', () => {
    $.ajax({
        url: 'controller/sitemap-gen.php',
        dataType: 'json',
        // data: {'id': id, 'prod': data},
        type: 'GET',
        success: function (response) {
            console.log(response)
            alerta(response.title, response.msg, response.class, 'ok');
        },
        error: function () {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    });

})
