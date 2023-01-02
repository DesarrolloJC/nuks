var tabla = $('#tblSlider').DataTable({
    ajax: "./controller/list.php", responsive: true, language: {
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
            sFirst: "Primero", sLast: "Último", sNext: "Siguiente", sPrevious: "Anterior"
        },
        oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        }
    }
});
$("input[type='file']").fileinput({
    language: 'es',
    showUpload: false,
    showCaption: false,
    browseClass: "btn btn-primary btn-block",
    allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg', 'webp']
});

$(document).on("click", ".pre_eraseFN", function () {
    var id = $(this).data('pro');
    Swal.fire({
        title: '¿Eliminar el Slider?',
        text: "El Slider se eliminará completamente y su imagen",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'controller/delete.php', type: 'GET', data: {"id": id}, function() {
                    alerta('Eliminado!', 'Slider Eliminado', 'success', 'OK');
                    table.ajax.reload();
                }
            });
        }
    });
});

function editar(id) {
    let form = $("#EdSl form");
    $.ajax({
        url: 'controller/get.php', data: {'id': id}, type: 'GET', success: function (r) {
            setTimeout(function () {
                form.find("input[name='id']").val(r.slider_id);
                form.find("input[name='name']").val(r.slider_name);
                form.find("input[name='url']").val(r.slider_url);
                form.find("input[name='imagen']").fileinput('destroy');
                form.find("input[name='imagen']").fileinput({
                    language: 'es',
                    showUpload: false,
                    showRemove: false,
                    showCaption: false,
                    browseClass: "btn btn-primary btn-block",
                    allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg', 'webp'],
                    initialPreviewAsData: true,
                    initialPreviewFileType: 'image',
                    initialPreview: '../../assets/images/sliderHome/' + r.slider_img
                });
                //Clonar input para meterle el archivo antes de apendarlo
                let imgUrl = '../../assets/images/sliderHome/' + r.slider_img

                loadURLToInputFiled(imgUrl, r.slider_img)

                $('#EdSl').modal('show');
            }, 50)
        }, error: function () {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    });
}


function loadURLToInputFiled(url, imgName) {
    getImgURL(url, (imgBlob) => {
        console.log(imgBlob)
        // Load img blob to input
        // WIP: UTF8 character error
        let file = new File([imgBlob], imgName, {type: "image/jpeg", lastModified: new Date().getTime()}, 'utf-8');
        let container = new DataTransfer();
        container.items.add(file);
        console.log(document.querySelectorAll("input[name='imagen']")[1].files);

        document.querySelectorAll("input[name='imagen']")[1].files = container.files;
        console.log(document.querySelectorAll("input[name='imagen']")[1].files);

    })
}

// xmlHTTP return blob respond
function getImgURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        callback(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}
