var tabla = $('#tblProduct').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    ajax: {
        type: 'GET', url: "./controller/list.php",
    },
    stateSaveCallback: function (settings, data) {
        localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
    },
    stateLoadCallback: function (settings) {
        return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
    },
    columns: [{data: 'nombre', name: "name"}, {data: 'imagen', name: "imagen"}, {
        data: 'codigo', name: "code_product"
    }, {data: 'url', name: 'url'}, {data: 'opciones', searchable: false, orderable: false}],
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

$("#prov").change(function () {
    console.log($("#prov").val())
    let opciones = $("#prov").val()
    let boton = document.getElementById('aceptar')
    if (opciones != 'default') {
        boton.classList.remove('disabled')
    }
    if (opciones == 'default') {
        boton.classList.add('disabled')
    }
});

$("#prov2").change(function () {

    if ($("#prov2").val() != 'default') {
        document.getElementById('aceptar2').classList.remove('disabled')
    }
    if ($("#prov2").val() == 'default') {
        document.getElementById('aceptar2').classList.add('disabled')
    }
});

$("#provUpd").change(function () {
    let opciones = $("#provUpd").val()
    let button = document.createElement('div')
    let parent = document.getElementById('file-in')
    let input = document.createElement("input")
    let div = document.createElement('div')
    div.classList.add("input-group")

    button.classList.add("btn")
    button.classList.add("btn-outline-secondary")
    button.textContent = "Subir"

    button.addEventListener('click', (e) => {
        e.preventDefault()

        let files = input.files;

        if (files.length > 0) {

            var formData = new FormData();
            formData.append("file", files[0]);

            var xhttp = new XMLHttpRequest();

            // Set POST method and ajax file path
            xhttp.open("POST", "controller/uploadExcel.php", true);

            // call on request changes state
            xhttp.onreadystatechange = function () {
                $("#aceptarUpd").html();
                $("#aceptarUpd").html("");
                $("#aceptarUpd").html('<img width="50px" src="../../img/loading.gif">');
                $("#aceptarUpd").attr("disabled", "disabled");
                $("#aceptarUpd").attr("disabled", "disabled");
                $("#cancel").attr("disabled", "disabled");
                if (this.readyState == 4 && this.status == 200) {
                    var response = this.responseText;
                    if (response) {

                        var form = $("form#update-prov");
                        var type = form.attr("method");
                        type = "GET"
                        var action = form.attr("action");
                        var confirmar = form.data("confirmar");
                        var correcto = false;
                        var data = null;
                        var file = null;

                        data = 'provUpd=' + opciones + '&file=' + response

                        sendToServer(form, action, type, data)
                    } else {
                        console.log(response)
                    }
                }
            };

            // Send request with data
            xhttp.send(formData);

        } else {
            alert("Please select a file");
        }

    })

    input.type = "file"
    input.classList.add("form-control")


    if (opciones == 6) {
        input.id = "ps-excel"
        button.id = 'ps-up'
        div.appendChild(input)
        div.appendChild(button)
        parent.appendChild(div)

        if (document.getElementById("po-excel")) {
            document.getElementById("po-excel").remove()
            document.getElementById("po-up").remove()
        }

    } else if (opciones == 7) {

        input.id = "po-excel"
        button.id = 'po-up'
        div.appendChild(input)
        div.appendChild(button)
        parent.appendChild(div)

        if (document.getElementById("ps-excel")) {
            document.getElementById("ps-excel").remove()
            document.getElementById("ps-up").remove()
        }

    } else {
        if (document.getElementById("ps-excel")) {
            document.getElementById("ps-excel").remove()
            document.getElementById("ps-up").remove()
        }
        if (document.getElementById("po-excel")) {
            document.getElementById("po-excel").remove()
            document.getElementById("po-up").remove()

        }
    }

    let boton = document.getElementById('aceptarUpd')
    if (opciones != 'default') {
        boton.classList.remove('disabled')
    }
    if (opciones == 'default') {
        boton.classList.add('disabled')
    }
});

function addProductMarkOne(data) {
    let select = $("#provUpd").val()
    select.addEventListener('change', function () {
        var selectedOption = this.options[select.selectedIndex]
        let id = selectedOption.value
    })

    $.ajax({
        url: 'controller/insert.php',
        dataType: 'json',
        data: {'id': id, 'prod': data},
        type: 'GET',
        success: function (response) {
            // var r = JSON.parse(response)
            if (response.success) {
                //alerta('Hecho!', 'Se han agregado los productos', 'success', 'Aceptar')
                alerta(response.title, response.msg, response.class, 'Aceptar');
                tabla.ajax.reload();
            } else {
                alerta('Error!', 'La respuesta tuvo un error', 'error', 'Aceptar');
            }
        },
        error: function () {
            alerta('Error!', 'No se pudo conectar al servidor', 'error', 'Aceptar');
        }
    });
}

$(document).on("click", ".pre_eraseFN", function () {
    var id = $(this).data('pro');
    Swal.fire({
        title: '¿Eliminar Producto?',
        text: "El producto se eliminará completamente",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'controller/delete.php', type: 'GET', data: {"id": id}, success: function (data) {
                    alerta('Eliminado!', 'Producto Eliminado', 'success', 'OK');

                    location.reload();
                    // $('#tblProduct').ajax.reload();
                }
            });
        }
    });
});

$("#aceptar2").on("click", () => {
    let prov = $("#prov2").val()
    $.get('controller/getBySupplier.php', {prov: prov, id: $("#product_code").val()}, function (response) {
        if (response.success) {

            Swal.fire({
                title: "Encontrado",
                html: JSON.parse(response.msg),
                icon: response.class,
                confirmButtonText: "ok",
                timer: 40
            })

            localStorage.setItem("cok", response.msg)

            let datos = JSON.parse(localStorage.getItem("cok"))

            let provSel = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[3]
            let nameIn = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[7]
            let urlProv = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[11]
            let urlProvH = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[13]
            let price = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[3]
            let priceClient = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[5].childNodes[3]
            let priceClientH = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[5].childNodes[5]
            let priceProvL1 = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[7].childNodes[3]
            let priceProvL1H = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[7].childNodes[5]
            let priceProvL2 = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[9].childNodes[3]
            let priceProvL2H = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[9].childNodes[5]
            let priceProvL3 = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[11].childNodes[3]
            let priceProvL3H = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[11].childNodes[5]
            let priceGeneral = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[13].childNodes[3]
            let priceGeneralH = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[13].childNodes[5]
            let codeProdInp = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[3].childNodes[1].childNodes[3]
            let prodCat = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[3].childNodes[3].childNodes[3]
            let prodStatus = document.getElementById("NwPro").childNodes[1].childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[3].childNodes[5].childNodes[1].childNodes[1]

            let descriptionEditor = CKEDITOR.instances.description;
            let informationEditor = CKEDITOR.instances.contenido;

            $.get("./controller/getOneCate.php", {name: datos.category[0]}, function (response) {
                prodCat.value = response
            });

            codeProdInp.value = datos.code_product[0]
            nameIn.value = datos.name[0]
            provSel.value = datos.prov[0].supplier_id
            urlProv.value = datos.prov[0].supplier_prod_url + datos.code_product[0]
            urlProvH.value = datos.prov[0].supplier_prod_url + datos.code_product[0]
            price.value = datos.price[0]

            priceClient.value = Math.round(price.value * (1.30) * 100) / 100
            priceClientH.value = Math.round(price.value * (1.30) * 100) / 100
            priceProvL1.value = Math.round(price.value * (1.25) * 100) / 100
            priceProvL1H.value = Math.round(price.value * (1.25) * 100) / 100
            priceProvL2.value = Math.round(price.value * (1.23) * 100) / 100
            priceProvL2H.value = Math.round(price.value * (1.23) * 100) / 100
            priceProvL3.value = Math.round(price.value * (1.21) * 100) / 100
            priceProvL3H.value = Math.round(price.value * (1.21) * 100) / 100
            priceGeneral.value = Math.round(price.value * (1.35) * 100) / 100
            priceGeneralH.value = Math.round(price.value * (1.35) * 100) / 100

            prodStatus.checked = datos.status
            descriptionEditor.setData(datos.description[0])
            informationEditor.setData(datos.info)


            document.getElementById("NwProPro").classList.remove("show")
            document.getElementById("NwProPro").style.display = "none"
            document.getElementById("NwPro").style.display = "block"
            document.getElementById("NwPro").classList.add("show")

        } else {
            alerta("Error", "Ocurrio un error", "danger", "ok")

            console.log("Pene")
        }
    });
})

$("#fixProds").on("click", () => {
    Swal.fire({
        title: 'Arreglando Problemas', allowEscapeKey: false, allowOutsideClick: false, didOpen: () => {
            swal.showLoading();
            $.ajax({
                url: "controller/fixProducts.php", context: document.body, data: {url: localStorage.getItem('urlbase')}
            }).done(function (response) {
                if (!response.includes("ERROR")) {
                    alerta("Exito!", "Problemas arreglados", "success", "Aceptar");
                    tabla.ajax.reload();
                } else {
                    console.log(response)
                    alerta("ERROR!", "Ocurrio un error, contacta al departamento de desarrollo", "danger", "Aceptar");
                }
            })
        }
    });

})
