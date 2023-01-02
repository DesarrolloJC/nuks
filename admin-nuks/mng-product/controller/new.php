<?php
header('Content-Type: application/json');
// header('Content-Type: text/event-stream');

session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';
require_once '../../mng-category/model/category.class.php';
require_once '../../php/soap.class.php';
require_once '../model/sliderProduct.class.php';
require_once '../../mng-supplier/model/supplier.class.php';
require_once '../model/progress.class.php';

$DB = new DBConnection;
$PROD = new Product($DB);
$CAT = new Category($DB);
$SLIDER = new Slider($DB);
$SUPPLIERS = new Supplier($DB);
$slides = array();

extract($_FILES);
for ($i = 0; $i < count($slider["name"]); $i++) {
    $slides[$i]["name"] = $slider["name"][$i];
    $slides[$i]["type"] = $slider["type"][$i];
    $slides[$i]["tmp_name"] = $slider["tmp_name"][$i];
    $slides[$i]["error"] = $slider["error"][$i];
    $slides[$i]["size"] = $slider["size"][$i];
}
//var_dump($slides);
//var_dump($slider["name"]);
//var_dump($slider["tmp_name"]);
//die();

extract($_POST);
//$id, $urlprod, $nombre, $prov_url, $price, $priceClient, $priceProv1, $priceProv2, $priceProv3, $priceGeneral, $status, $categoria, $description, $contenido,
$newFileName = "";

if ($img) {
    $route = '../../../img/product/';
    $webpRoute = $route . "webp/";
    $filename = $img["name"];
    move_uploaded_file($img['tmp_name'], $route . $filename);
    $newFileName = "webp/" . explode(".", $filename)[0] . ".webp";
    $img = imagecreatefromjpeg($route . $filename);
    imagepalettetotruecolor($img);
    imagealphablending($img, true);
    imagesavealpha($img, true);
    imagewebp($img, $route . $newFileName, 80);
    imagedestroy($img);
}

$PRODUCT['code'] = $cod;
$PRODUCT['category'] = $categoria[0];
$PRODUCT['color'] = $colors;
$PRODUCT['name'] = $nombre;
$PRODUCT['url'] = $PROD->makeURL(($nombre));
$PRODUCT['description'] = $PROD->SanitizarTexto($description);
$PRODUCT['info'] = $PROD->SanitizarTexto($contenido);
$PRODUCT['img'] = $newFileName;
$PRODUCT['code_product'] = $PRODUCT["code"];
$PRODUCT['product_depen'] = 0;
$PRODUCT['price'] = $price;
$PRODUCT['price_general'] = $priceGeneral;
$PRODUCT['price_client'] = $priceClient;
$PRODUCT['price_distributor_level_one'] = $priceProv1;
$PRODUCT['price_distributor_level_two'] = $priceProv2;
$PRODUCT['price_distributor_level_three'] = $priceProv3;
$PRODUCT['prov_website'] = $prov_url . $PRODUCT['code'];
$PRODUCT['supplier'] = $prov;
$PRODUCT['status'] = $status ? 1 : 0;
//var_dump($PRODUCT);
//die();


if ($PROD->insert($PRODUCT)) {
    $prodId = $PROD->getLastInsertedProd();

    for ($i = 0; $i < count($slides); $i++) {
        $slide = $slides[$i];
        $routeSL = '../../../img/product/';
        $webpRouteSL = $routeSL . "webp/";
        $filenameSL = $slide["name"];
        move_uploaded_file($slide["tmp_name"], $routeSL . $filenameSL);
        $newFileNameSL = "webp/" . explode(".", $filenameSL)[0] . ".webp";
        $imgSL = imagecreatefromjpeg($routeSL . $filenameSL);
        imagepalettetotruecolor($imgSL);
        imagealphablending($imgSL, true);
        imagesavealpha($imgSL, true);
        imagewebp($imgSL, $routeSL . $newFileNameSL, 80);
        imagedestroy($imgSL);

        $SLI["prod_id"] = $prodId["product_id"];
        $SLI["slider_img"] = $newFileNameSL;

        if (!$SLIDER->insert($SLI)) {
            echo json_encode("PITO WE");
            die();
        }
    }
    echo json_encode(array('success' => true, 'title' => 'Exito!', 'msg' => 'Productos agregados correctamente.', 'class' => 'success', 'final' => 'tabla.
        ajax.reload(); $("#UpdPro").modal("hide");'));


} else {
    echo json_encode(array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error al insertar, <br> i:  ,  COUNT: <br>', 'class' => 'error'));

}

