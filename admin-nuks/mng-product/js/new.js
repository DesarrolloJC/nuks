var IDPROD = 0;
var prov_url
$(function () {

    $.get("./controller/getCates.php", {id: 0}, function (response) {
        $("select[name='categoria[]']").html(response);
    });

    $("input[name='slider[]']").fileinput({
        language: 'es',
        overwriteInitial: false,
        showUpload: false,
        showRemove: false,
        showCaption: false,
        browseClass: "btn btn-primary btn-block",
        allowedFileExtensions: ['jpg', 'png', 'gif', 'webp'],
        deleteUrl: "./controller/img.delete.php",
        initialPreviewAsData: true,
    })


    $("input[name='img']").fileinput({
        language: 'es',
        overwriteInitial: true,
        showUpload: false,
        showRemove: false,
        showPreview: true,
        browseClass: "btn btn-primary",
        allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg', 'webp'],
        initialPreviewAsData: true,
    })
    // loadURLToInputFiled(imgUrl, r.slider_img) //TODO FInish this bitch

    $(document).on("change", "select[name='categoria[]']", function () {
        let idc = $(this).val();
        var parent = $(this).parent();
        var next = null;

        while ($(this).next('select').length) {
            $(this).next('select').remove();
        }

        next = $('<select class="form-control" name="categoria[]"></select>');

        $.get("./controller/getCates.php", {id: idc}, function (response) {
            if (response != "0") {
                parent.append(next);
                next.html(response);
            }
        }).fail(function () {
            alerta("Error", "No se pudieron recuperar las categorias", "error")
        });

    });

    $("#price").on("keyup", () => {
        let price = $("#price").val()
        $("#priceClient").val(Math.round(parseInt(price) * (1.30) * 100) / 100)
        $("#priceClientH").val(Math.round(parseInt(price) * (1.30) * 100) / 100)
        $("#priceProv1").val(Math.round(parseInt(price) * (1.25) * 100) / 100)
        $("#priceProv1H").val(Math.round(parseInt(price) * (1.25) * 100) / 100)
        $("#priceProv2").val(Math.round(parseInt(price) * (1.23) * 100) / 100)
        $("#priceProv2H").val(Math.round(parseInt(price) * (1.23) * 100) / 100)
        $("#priceProv3").val(Math.round(parseInt(price) * (1.21) * 100) / 100)
        $("#priceProv3H").val(Math.round(parseInt(price) * (1.21) * 100) / 100)
        $("#priceGeneral").val(Math.round(parseInt(price) * (1.35) * 100) / 100)
        $("#priceGeneralH").val(Math.round(parseInt(price) * (1.35) * 100) / 100)
    })


    $("#status").on("change", () => {
        $("#statusH").val(document.getElementById("status").checked)
    })

    $("#provAdd").on("change", () => {
        $.get('../mng-supplier/controller/get.php', {id: $("#provAdd").val()}, function (response) {
            // console.log(response);
            $("#supC").val(response["supplier_code"])
            $("#prov_url").val(response["supplier_prod_url"])
            $("#prov_urlH").val(response["supplier_prod_url"])
        });
    })

    $("#prov_url").on("keyup", () => {
        prov_url = $("#prov_url").val()
        $("#prov_urlH").val($("#prov_url").val())
        $("#prov_url").val($("#prov_urlH").val())
    })


    $("#cod").on("keyup", () => {
        document.getElementById('prov_url').value = document.getElementById('prov_urlH').value + document.getElementById('cod').value
    })

    $("#cod").on("focusout", () => {
        document.getElementById('prov_url').value = document.getElementById('prov_urlH').value + document.getElementById('cod').value
        $("#prov_urlH").val($("#prov_url").val())
    })
});


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
