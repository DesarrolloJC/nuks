<?php

session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';
require_once '../../php/soap.class.php';

$DB = new DBConnection;
$SUP = new Supplier($DB);
$PROD = new Product($DB);
$API = new ApiRestSoap;

$prov = $_GET["prov"];
$prod_id = $_GET["id"];

$IMGURL = '../../../img/product/';

$suppliers = $SUP->getAll();

switch ($prov) {
    case 222:
        $supplier = $SUP->getByCode($prov);
        $PRODUCT = $PROD->insertProductMacma($prod_id);
        $PRODUCT["prov"] = $supplier;

        $code_product = $supplier[0]['supplier_code'] . '-' . $PRODUCT['code_product'];


        $name = $PROD->makeURL($PRODUCT['name']);
        $CODE_PRODUCT = $PRODUCT['code_product'];

        if (!file_exists($IMGURL . $name . '.jpg')) {
            $file = upLoadIMG($supplier[0]['supplier_api'], $CODE_PRODUCT, $IMGURL, $codeColor, 0, 1, $PRODUCT['name']);
        }

        $PRODUCT['code'] = $CODE_PRODUCT;
        $PRODUCT['name'] = $PROD->SanitizarTexto($PRODUCT['name']);
        $PRODUCT['url'] = $PROD->makeURL($PRODUCT['name']);
        $PRODUCT['description'] = $PROD->SanitizarTexto($PRODUCT['description']);
        $PRODUCT['img'] = $file;
        $PRODUCT['code_product'] = $code_product;
        $PRODUCT['product_depen'] = 0;
        $PRODUCT['price'] = $PRODUCT['price'][0];
        $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
        $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
        $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
        $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
        $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;
        $PRODUCT['prov_website'] = $suppliers[$PROV - 1]['supplier_prod_url'] . $PRODUCT['code'];
        $PRODUCT['supplier'] = $suppliers[$PROV - 1]['supplier_code'];

        var_dump($PRODUCT);
        die();

        $RES = $CAT->getCategory($PROD->SanitizarTexto($PRODUCT['category']));

        if (!count($RES)) {
            //No se encontro la categoria
            addCategory($PRODUCT['category'],);
            $CATRES = $CAT->getCategory($PROD->SanitizarTexto($PRODUCT['category']));
            $PRODUCT['category'] = $CATRES[0]['cat_id'];
        } else {
            $PRODUCT['category'] = $RES[0]['cat_id'];
        }

        if ($PROD->insert($PRODUCT)) {
            //Se inserta el producto principal y se agregan los productos hijos
            $ID = $DB->GetLastInsertID(); //Se obtiene el ID del producto principal o padre
            $lastProduct = $PROD->getById($ID);
            $lastProductId = $lastProduct['code_product'];

            $_SESSION['lid'] = $lastProductId;

            $CHILDS = $PROD->getCodesProductsMacma($CODES[$i]); //Se obtiene el numero de imagenes adicionales o productos hijo

            if (count($CHILDS) > 0) {
                //Si contiene productos hijo se agregaran en la base de datos dentro del FOREACH

                $SL = [];
                foreach ($CHILDS as $C) {
                    $codeColor = $C[0];

                    $PRODUCT['name'] = $name;
                    $PRODUCT['color'] = $PROD->SanitizarTexto($codeColor);

                    $SL['prod_id'] = $ID;

                    $file = upLoadSlide($suppliers[$PROV - 1]['supplier_api'], $CODE_PRODUCT, $IMGURL, $codeColor, 0, $PROV, $PRODUCT['name']);

                    //Slider section
                    if (file_exists($IMGURL . $file)) {
                        $SL['slider_img'] = $file;
                    } else {
                        $SL['slider_img'] = '../../../img/relleno-art.png';
                    }
                    $SL['slider_reference'] = $codeColor;

                    $SLIDER->insert($SL);
                    $SL['slider_img'] = '';
                }
            }

            if (!validateProd($PROD, $SLIDER, $DB)) {
                $successCount++;
            }
        }


        $name = $PROD->makeURL(($PRODUCT['name']));

        $file = upLoadIMG($suppliers[$suppliers["supplier_id"]]["supplier_api"], $PRODUCT['code_product'], $IMGURL, "", 0, $prov, $PRODUCT["name"]);

        echo json_encode(array('success' => true, 'title' => 'Exito!', 'msg' => json_encode($PRODUCT), 'class' => 'success', 'final' => ''));
        break;
}


function upLoadIMG($urlIMG, $code, $dir, $color, $try, $type, $product_name)
{
    $product_filename = null;
    switch ($type) {
        case 1:
            $product_filename = $product_name . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG . $code . ".jpg");
            break;
        case 2:
            $product_filename = $color . '-' . $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG . $product_filename); //Esta es la url de la imagen a descargar
            break;
        case 3:
            $product_filename = $color . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG);
            break;
        case 4:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 5:
            $product_filename = str_replace(" ", "-", $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init(str_replace(" ", "%20", $urlIMG)); //Esta es la url de la imagen a descargar
            break;
        case 6:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 10:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
    }
    $fp = fopen($dir . $product_filename, 'wb'); // Aqui se guarda la imagen

//    dump($urlIMG);

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    return $product_filename;

}

function upLoadSlide($urlIMG, $code, $dir, $color, $try, $type, $product_name)
{
    switch ($type) {
        case 1:
            $product_filename = $product_name . '-' . $color . '.jpg';
            $ch = curl_init($urlIMG . $code . "-" . $color . ".jpg");
            break;
        case 2:
            $product_filename = $color . '-' . $code . '.jpg';
            $ch = curl_init($urlIMG . $product_filename); //Esta es la url de la imagen a descargar
            break;
        case 3:
            $product_filename = str_replace(" ", "-", $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 4:
            $product_filename = str_replace(" ", "-", $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 5:
            $product_filename = str_replace(" ", "-", $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 6:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            //            dump($urlIMG . "---" . $code . "---" . $dir . "---" . $color . "---" . $try . "---" . $type . "---" . $product_name);
            break;
        case 10:
            $product_filename = explode(".", explode("/", $urlIMG)[3])[0] . ".jpg"; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar;
            break;
    }
    $fp = fopen($dir . $product_filename, 'wb'); // Aqui se guarda la imagen

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
//    dump($dir);

    return $product_filename;
}
