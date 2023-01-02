var IDPROD = 0;

$(function () {

    $.get("./controller/getCates.php", {id: 0}, function (response) {
        $("select[name='categoria[]']").html(response);
    });

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

    setTimeout(function () {
        $.get('./controller/get.php', {id: id}, function (response) {
            const r = response.producto
            // console.log("R es: " + r);
            // let arr = Object.values(r);
            console.log(r);
            IDPROD = r.product_id
            $("input[name='id']").val(r.product_id)
            $("input[name='urlprod']").val(r.url)
            $("input[name='nombre']").val(r.name)
            $("input[name='prov_url']").val(r.prov_website)
            $("#prov2").val(parseInt(r.supplier))
            $("input[name='price']").val(r.price)
            $("input[name='priceClient']").val(r.price_client)
            $("input[name='priceProv1']").val(r.price_distributor_level_one)
            $("input[name='priceProv2']").val(r.price_distributor_level_two)
            $("input[name='priceProv3']").val(r.price_distributor_level_three)
            $("input[name='priceGeneral']").val(r.price_general)
            $("input[name='colors']").val(r.color)
            $("select[name='categoria[]']").val(response.categorias[0])

            if (r.status == 1) {
                document.getElementById("status").click()
            }

            var parent = $("select[name='categoria[]']").parent();
            for (var i = 1, len = response.categorias.length; i < len; i++) {
                var padre = response.categorias[i - 1];
                var valor = response.categorias[i];
                $.get("./controller/getCates.php", {id: padre, val: valor}, function (res) {
                    setTimeout(function () {
                        parent.append('<select class="form-control" name="categoria[]"></select>');
                        $("select[name='categoria[]']").last().append(res);
                    }, 100);
                });
            }

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
                initialPreviewFileType: 'image',
                initialPreview: response.preview,
                initialPreviewConfig: response.config
            });

            $("input[name='img[]']").fileinput({
                language: 'es',
                overwriteInitial: true,
                showUpload: false,
                showRemove: false,
                showPreview: true,
                browseClass: "btn btn-primary",
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg', 'webp'],
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreview: '../../img/product/' + r.img
            });
            // console.log("La imagen es: " +

            CKEDITOR.instances["contenidoEdit"].setData(r.info);
            CKEDITOR.instances["description2"].setData(r.description);

        });
    }, 100);
    $("#price").on("keyup", () => {
        $("#priceClient").val(Math.round($("#price").val() * (1.30) * 100) / 100)
        $("#priceClientH").val(Math.round($("#price").val() * (1.30) * 100) / 100)
        $("#priceProv1").val(Math.round($("#price").val() * (1.25) * 100) / 100)
        $("#priceProv1H").val(Math.round($("#price").val() * (1.25) * 100) / 100)
        $("#priceProv2").val(Math.round($("#price").val() * (1.23) * 100) / 100)
        $("#priceProv2H").val(Math.round($("#price").val() * (1.23) * 100) / 100)
        $("#priceProv3").val(Math.round($("#price").val() * (1.21) * 100) / 100)
        $("#priceProv3H").val(Math.round($("#price").val() * (1.21) * 100) / 100)
        $("#priceGeneral").val(Math.round($("#price").val() * (1.35) * 100) / 100)
        $("#priceGeneralH").val(Math.round($("#price").val() * (1.35) * 100) / 100)

    })

});
