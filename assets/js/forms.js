const validar = {
    letras: "^$|^[A-Za-zñÑáéíóúüÁÉÍÓÚÜ ]+$",
    numletras: "^$|^[A-Za-z0-9ñÑáéíóúüÁÉÍÓÚ#\\.,:_/\\-+ ]+$",
    texto:
        "^$|^[A-Za-z0-9ñÑáéíóúüÁÉÍÓÚÜ\\.,:;_\\-+/*=<>#$%@&¢£¥€©®[\\]{}()¿?¡!\\\"\\' ]+$",
    textarea:
        "^$|^[A-Za-z0-9ñÑáéíóúüÁÉÍÓÚÜ\\.,:;_\\-+/*=<>#$%@&¢£¥€©®[\\]{}()¿?¡!\\\"\\'\\n ]+$",
    correo:
        "^$|^[A-Za-z0-9\\-_\\.]+@[a-z0-9]+(\\.mx|\\.com|\\.com\\.mx|\\.net|\\.net\\.mx|\\.es|\\.org\\.mx|\\.org|\\.gob|\\.gob\\.mx)$",
    web: "^$|^(https:\\/\\/|http:\\/\\/){0,1}[A-Za-z0-9\\.]+(\\.mx|\\.com|\\.com\\.mx|\\.net|\\.net\\.mx|\\.es|\\.org\\.mx|\\.org|\\.gob|\\.gob\\.mx){1}[\\/A-Za-z0-9=?&\\.]*$",
    telefono: "^$|^[0-9 ]{10,15}$",
    clave: "^$|^[a-zA-Z0-9]+$",
    fecha:
        "^$|^(19|20)[0-9]{2}\\-(01|02|03|04|05|06|07|08|09|10|11|12)\\-[0-9]{2}$",
    hora: "^$|^[0-9]{2}:{1}[0-9]{2}:{0,1}[0-9]{0,2}$",
    entero: "^$|^[0-9]+$",
    decimal: "^$|^[0-9]+\\.{0,1}[0-9]{0,4}$",
    password:
        "^$|^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&#=+\\-])[A-Za-z\\d@$!%*?&#=+\\-]{8,}$",
};

var editorck = false;
var textBtn = "";


$("form").submit(function (event) {
    event.preventDefault();
    var form = $(this);
    var type = form.attr("method");
    var action = form.attr("action");
    var confirmar = form.data("confirmar");
    var correcto = false;
    var data = null;
    var file = null;

    correcto = EsValido(form);

    if (correcto) {
        if (editorck) {
            for (var ck in CKEDITOR.instances) {
                CKEDITOR.instances[ck].updateElement();
            }
        }

        /*if ($('#ps-excel')) {
        file = $('#ps-excel').prop('files');
        // data.append(form[1])
        data = new FormData(form[0])
    } else {
        data =
            type == "GET" || type == "get" ? form.serialize() : new FormData(form[0]);
    }
*/

        data =
            type == "GET" || type == "get" ? form.serialize() : new FormData(form[0]);

        if (confirmar) {
            var title = form.data("msg");
            Swal.fire({
                title: title,
                text: "¿Los datos ingresados son correctos?",
                type: "question",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.value) {
                    sendToServer(form, action, type, data);
                }
            });
        } else {
            sendToServer(form, action, type, data);
        }
    }
});

function sendToServer(formulario, url, method, datos) {
    $.ajax({
        url: url,
        type: method,
        data: datos,
        cache: false,
        processData: false,
        contentType: false,
        statusCode: {
            504: function () {
                alerta("Error", "Se agotó el tiempo, intentalo más tarde", "error", "Aceptar");
            }
        },
        beforeSend: function () {
            DescButtonsV(formulario);
            DisplayProgBar(formulario);
            // startTask(url)
        },
        complete: function (response) {
            // debugger
            ActButtonsV(formulario);
            editorck = false;
            // formulario.trigger("reset");

        },
        error: function (response) {
            // debugger

            if (response.responseText.includes("error") || response.responseText.includes("warning")) {
                alerta("Error", response.responseText, "error", "Aceptar");
            } else if (response.responseText.includes("retry")) {
                alerta("Error", response.responseText, "warning", "Aceptar");
            } else {
                alerta("Error", response.responseText, "error", "Aceptar");
            }
        },
        success: function (response) {
            alerta(response.title, response.msg, response.class, "Aceptar");
            if (response.success) {
                formulario.trigger("reset");
            }
            eval(response.final);
            // console.log(formulario);
            setTimeout(function () {
                // console.log(response.final)
                formulario.trigger("reset");
            }, 3500);
        },
    });
}

//Botones de formularios
function DescButtonsV(f) {
    textBtn = f.find("button[type='submit']").html();
    f.find("button[type='submit']").html("");
    // f.find("button[type='submit']").html('<img width="50px" src="' + url + 'images/Ellipsis-1.5s-200px.gif">');
    f.find("button[type='submit']").html('<img width="50px" src="../../img/loading.gif">');
    f.find("button[type='submit']").attr("disabled", "disabled");
    f.find("button[type='reset']").attr("disabled", "disabled");
}

function DisplayProgBar(form) {
    if (!document.getElementById("progressBar")) {
        let div = document.createElement("div")
        let div2 = document.createElement("div")
        div.classList.add("progress")
        div2.classList.add("progress-bar")
        div.setAttribute("id", "progressBarContainer")
        div2.setAttribute("id", "progressBar")

        // div2.style.width = "50%"
        div.appendChild(div2)
        // form[0].childNodes[1].appendChild(div)
    }

}

function ActButtonsV(f) {
    f.find("button[type='submit']").html("");
    f.find("button[type='submit']").html(textBtn);
    f.find("button[type='submit']").prop("disabled", null);
    f.find("button[type='reset']").prop("disabled", null);
}

//Validar entradas
function EsValido(form) {
    var valido = true;

    form.find(":input").each(function () {
        var tipo = $(this).prop("type");
        var valor = $(this).val();
        var msg = $(this).data("msg");
        var bool = true;

        switch (tipo) {
            case "password":
                bool = CheckRegex(validar.password, valor);
                break;
            case "number":
                bool = CheckRegex(validar.entero, valor);
                break;
            case "email":
                bool = CheckRegex(validar.correo, valor);
                break;
            case "tel":
                bool = CheckRegex(validar.telefono, valor);
                break;
            case "text":
                var regla = $(this).data("regla");
                bool = CheckRegex(validar[regla], valor);
                break;
            case "file":
                bool = true
                break;
            case "textarea":
                if ($(this).attr("class") == "ckeditor") {
                    bool = true;
                    editorck = true;
                } else {
                    bool = CheckRegex(validar.textarea, valor);
                }
                break;
        }

        if (!bool) {
            alerta("Error!", msg, "warning");
            $(this).focus();
            return (valido = false);
        }
    });

    return valido;
}

function CheckRegex(regex, string) {
    const Regex = RegExp(regex, "i");
    var valido = false;
    valido = Regex.test(string);
    return valido;
}
