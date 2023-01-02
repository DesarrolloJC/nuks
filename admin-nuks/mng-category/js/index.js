var id2 = 0;
var id3 = 0;

var tabla = $('#resultados').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: 'GET', url: "./controller/list.php", data: {
            nivel: 0
        }
    },
    stateSaveCallback: function (settings, data) {
        localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
    },
    stateLoadCallback: function (settings) {
        return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
    },
    columns: [{ data: 'orden', name: "cat_order" }, { data: 'nombre', name: "cat_name" }, {
        data: 'opciones',
        searchable: false,
        orderable: false
    },],
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
        sInfoThousands: " , 1",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: " Primero ", sLast: " Último ", sNext: " Siguiente ", sPrevious: " Anterior "
        },
        oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    }
});

var tabla2 = $('#resultados2').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: 'GET', url: "./controller/list.php", data: function (d) {
            d.nivel = 1, d.padre = id2
        }
    },
    stateSaveCallback: function (settings, data) {
        localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
    },
    stateLoadCallback: function (settings) {
        return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
    },
    columns: [{ data: 'orden', name: "cat_order" }, { data: 'nombre', name: "cat_name" }, {
        data: 'opciones',
        searchable: false,
        orderable: false
    },],
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
        sInfoThousands: " , 1",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: " Primero ", sLast: " Último ", sNext: " Siguiente ", sPrevious: " Anterior "
        },
        oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    }
});

var tabla3 = $('#resultados3').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: 'GET', url: "./controller/list.php", data: function (d) {
            d.nivel = 2, d.padre = id3
        }
    },
    stateSaveCallback: function (settings, data) {
        localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
    },
    stateLoadCallback: function (settings) {
        return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
    },
    columns: [{ data: 'orden', name: "cat_order" }, { data: 'nombre', name: "cat_name" }, {
        data: 'opciones',
        searchable: false,
        orderable: false
    },],
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
        sInfoThousands: " , 1",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: " Primero ", sLast: " Último ", sNext: " Siguiente ", sPrevious: " Anterior "
        },
        oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    }

});

select(0, 0, "select[name='cat']");

/***** Buscar hijos *****/
$(document).on("click", ".fa-list-ul", function () {
    var ni = $(this).data("nivel");
    if (ni == 0) {
        id2 = $(this).data("id");
        tabla2.ajax.reload();
    } else {
        id3 = $(this).data("id");
        tabla3.ajax.reload();
    }
});

$("#ncat").change(function () {
    select($(this).val(), 1, "#nscat")
});

$("#ecat").change(function () {
    select($(this).val(), 1, "#escat")
});

/***** Manipular categorias *****/

$(document).on("click", ".edit", function () {
    var id = $(this).data('id');
    var form = $("#ed-cat form");

    $.get('./controller/get.php', { id: id }, function (response) {
        console.log(response)
        form.find("input[name='id']").val(response.cat_id);
        form.find("input[name='nombre']").val((response.cat_name));
        switch (response.cat_level) {
            case '0':
                form.find("select[name='cat']").val(0);
                form.find("select[name='subcat']").val(0);
                break;
            case '1':
                select(response.cat_depen, 1, "#escat");
                form.find("select[name='cat']").val(response.cat_depen);
                form.find("select[name='subcat']").val(0);
                break;
            case '2':
                select(response.depende2, 1, "#escat");
                form.find("select[name='cat']").val(response.depende2);
                setTimeout(function () {
                    form.find("select[name='subcat']").val(response.cat_depen);
                }, 100);
                break;
            default:
                console.log("Salto todo el switch");
        }
    }).fail(function () {
        alerta("Error!", "No se ha podido recuperar la información de la categoría.", "error", "Aceptar");
    });

    $("#ed-cat").modal("show");
});

$(document).on("click", ".del", function () {
    var id = $(this).data('id');
    checkChildProds(id);
});

$(document).on("click", ".reassign", function () {
    var id = $(this).data('id');
    reassignCat(id)
});

$(document).on("click", ".fa-sort", function () {
    var id = $(this).data('id');
    var po = $(this).data('orden');
    var form = $("#orden form");
    form.find("input[name='id']").val(id);
    form.find("input[name='pos']").val(po);
    $('#orden').modal('show');
});

function select(padre, nivel, select) {
    var params = {
        id: padre, ni: nivel
    };
    $.get('./controller/getcats.php', params, function (response) {
        $(select).html('')
        $(select).html(response)
    });
}

function reassignCat(id, del) {
    $.ajax({
        url: './controller/getallcats.php', type: 'GET', data: {}, success: function (r) {
            Swal.fire({
                title: '¿Quieres reasignar los productos?',
                // text: "No se que poner aquí xd",
                type: 'info',
                html: "<h4>Categorias</h4><select class='form-control' id='new-cat'>" + r + "</select>",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
            }).then((result, input) => {
                if (result.value) {
                    let newCatId = document.getElementById("new-cat").value
                    $.ajax({
                        url: './controller/reassigncat.php', type: 'GET', data: { "id": id, "newId": newCatId }, success: function (r) {
                            if (del) {
                                console.log("DICK");
                                deleteCat(id);
                                tabla.ajax.reload();
                            } else {
                                alerta(r.title, r.msg, r.class, 'Aceptar');
                                tabla.ajax.reload();
                            }

                        }
                    });
                }
            });
        }
    });
}

function deleteCat(id) {
    Swal.fire({
        title: '¿Eliminar Categoría?',
        text: "Dejará de existir en el sistema, sus imágenes también se eliminarán",
        type: 'warning',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: './controller/delete.php', type: 'GET', data: { "id": id }, success: function (r) {
                    alerta(r.title, r.msg, r.class, 'Aceptar');
                    tabla.ajax.reload();
                }
            });
        }
    });
}
function checkChildProds(id) {
    $.ajax({
        url: './controller/getchilds.php', type: 'GET', data: { "id": id }, success: function (r) {
            let prods = JSON.parse(r)
            if (prods.length > 0) {
                reassignCat(id, "del")
            } else {
                deleteCat(id)
            }

        }
    });

}