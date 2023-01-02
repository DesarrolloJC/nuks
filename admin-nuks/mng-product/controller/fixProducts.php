<?php

//#region inclusion forzada xd
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../../mng-category/model/category.class.php';
require_once '../../mng-product/model/porduct.class.php';
require_once '../../mng-supplier/model/supplier.class.php';

$DB = new DBConnection;

$CATES = new Category($DB);
$PROD = new Product($DB);
$SUP = new Supplier($DB);

//#endregion

//#region definicion de variables
$arrayForbidden = array("", 0, "N/A", "n/a", " ");
$CATESTODELID = array();

$LISTO = array('success' => true, 'title' => '¡Exito!', 'msg' => 'Reasignación exitosa.', 'class' => 'success');
$ERRGN = array('success' => false, 'title' => '¡Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$CATESTOREASSIGN = getDups($PROD, $CATES);

//$CATES->invalidCats();
//die();

$OTROCAT = $CATES->getCategory("Otro");

$notVald = reassign(getSmolCats($PROD, $CATES), $CATES, $OTROCAT, $PROD);

$productDups = prodDepen($PROD, $CATES);


//#endregion

main($arrayForbidden, $CATES, $OTROCAT, $CATESTOREASSIGN, $ERRGN, $LISTO, $PROD, $SUP, $_GET["url"], $productDups);

//#region functions

/**
 * @param $arrayForbidden
 * @param $CATES
 * @param $OTROCAT
 * @param $CATESTOREASSIGN
 * @param $ERRGN
 * @param $LISTO
 * @param $PROD
 * @param $SUP
 * @param $URL
 * @return void
 */
function main($arrayForbidden, $CATES, $OTROCAT, $CATESTOREASSIGN, $ERRGN, $LISTO, $PROD, $SUP, $URL, $productDups)
{
    for ($i = 0; $i < count($arrayForbidden); $i++) {
        if ($CATES->getCategoryId($arrayForbidden[$i])) {
            $CATESTODELID[] = $CATES->getCategoryId($arrayForbidden[$i]);
//            var_dump($CATES->getCategoryId($arrayForbidden[$i]));
        } else {
            $productosAReasignar = $PROD->getByCate($arrayForbidden[$i]);
            var_dump(count($productosAReasignar));
            $p = 0;
            foreach ($productosAReasignar as $pene) {
                $pene["category"] = $OTROCAT[0]["cat_id"];
                var_dump($pene);
                $PROD->update($pene, $pene["product_id"]);
                $p++;
            }
            var_dump($p);
        }
    }

    validProd($CATESTODELID, $CATES, $OTROCAT, $PROD, $LISTO, $ERRGN, $URL, $SUP, $CATESTOREASSIGN, $productDups);
}

/**
 * @param $CATESTODELID
 * @param $CATES
 * @param $OTROCAT
 * @param $PROD
 * @param $LISTO
 * @param $ERRGN
 * @param $URL
 * @param $SUP
 * @param $CATESTOREASSIGN
 * @return void
 */
function validProd($CATESTODELID, $CATES, $OTROCAT, $PROD, $LISTO, $ERRGN, $URL, $SUP, $CATESTOREASSIGN, $productDups)
{

    try {
        $j = 0;
        foreach ($productDups as $productDup) { //Se relacionan los productos hijos con los padres para que asi no se muestren n productos en la misma vista
            foreach ($productDup["children"] as $item) {
                if ($PROD->update($item, $item["product_id"])) {
//                    var_dump("NICE");
                } else {
//                    var_dump("NOT NICE");
                }
            }
        }

        //#region category shit
        foreach ($CATESTOREASSIGN as $CATE) { //Aqui se eliminan las categorias que no contengan ningun producto
            foreach ($CATE["ids"] as $id) {
                if (count($PROD->getByCate($id)) == 0) {
                    $CATES->removeCat($id);
                    echo "Categoria borrada";
                    echo "\n";
                }
                $j++;
            }
        }


        //#endregion

        if ($shit = $PROD->getAll()) { //Se jalan todos los productos con campos vacios o que contengan nombre duplicado
            foreach ($shit as $prod) {

                $newUrl = $SUP->getByCode($prod["supplier"])[0]["supplier_prod_url"];

//                if (!isValidUrl($prod["prov_website"])) {
//                    $newUrl = "N/A";
//                }
//todo finish me

                if (renameProductCode($prod, $SUP) != "no") {
                    $newProdCode = renameProductCode($prod, $SUP); //Se genera un nuevo codigo de producto para los productos que no tengan
                    $prod["code_product"] = $newProdCode;
                }

//                $newName = renameNameProd($prod); //Se renombran los productos que no contengan nombre alguno
                $newCode = renameCode($prod); //Se genera un nuevo codigo para los productos que no contengan
//                $prod["name"] = $newName;
                $prod["code"] = $newCode;
                $prod["url"] = $PROD->makeURL($prod["name"]);
                $g4 = $SUP->getByName("G4");
                $newProdUrl = $newUrl . $newCode;
                $prod["prov_website"] = $newProdUrl;
//                var_dump($prod);
//                validateImgs($prod, $URL, $SUP->getAll(), $PROD);
//                die();
                if (!$PROD->update($prod, $prod["product_id"])) {
                    echo "Ocurrio un error";
                    echo "\n";
                    die();
                }
//                echo json_encode($LISTO);

            }
        }

    } catch
    (Exception $exception) {
        var_dump($exception);
        die();
    }
}

/**
 * @param $CATESTODEL
 * @param $CATES
 * @param $OTROCAT
 * @param $PROD
 * @return string
 */
function reassign($CATESTODEL, $CATES, $OTROCAT, $PROD)
{
    $resp = "";
    for ($i = 0; $i < count($CATESTODEL); $i++) {

        $C = $CATESTODEL[$i];

        if ($PROD->updateCategory($C["cat_id"], $OTROCAT[0]["cat_id"])) {
            $CATES->removeCat($C["cat_id"]);
            if ($PROD->updateCategory(0, $OTROCAT[0]["cat_id"])) {
                $resp .= 1;
            }
        } else {
            $resp .= 0;
        }
    }
    return $resp;
}

/**
 * @param $supplier
 * @param $PROD
 * @return string|void
 */
function getDuplicatedProds($supplier, $PROD)
{
    $response = "";
    $suppliers = $supplier->getAll();
    foreach ($suppliers as $sup) {
        $dupliProds = $PROD->getBySupplier($sup['supplier_code']);

        for ($i = 0; $i < count($dupliProds); $i++) {
            $count = $dupliProds[$i]["COUNT(name)"];
            for ($j = 0; $j < $count; $j++) {
                $PRODUCT = $PROD->getByProdName($dupliProds[$i]["name"])[0];
                $currProdColor = $PRODUCT["color"];
                if (count($PRODUCT["name"]) > 0) {
                    $currProdName = $PRODUCT["name"];
                } else {
                    $currProdName = renameNameProd($PRODUCT);
                }
                $PRODUCT["url"] = $PROD->makeURL($currProdName . "-" . $currProdColor);
                $PRODUCT["name"] = $PROD->makeNombre($currProdName . "-" . $currProdColor);

                if ($PROD->update($PRODUCT, $PRODUCT["product_id"])) {
                    $response .= 1;
                } else {
                    $response .= 0;
                }
            }
        }
        return $response;
    }

}

/**
 * @param $prod
 * @return string
 */
function renameProductCode($prod, $sup)
{

    return $prod["supplier"] . "-" . $prod["code"];
}

/**
 * @param $prod
 * @return mixed|string
 */
function renameNameProd($prod)
{


    if ($prod["name"] == "" && $prod["description"] != "") {

        $color = count(explode(",", $prod["color"])) <= 1 ? $prod["color"] : "multicolor";
//        $newName = $prod["name"] . "-" . $color;

        $newName = $prod["description"] . "-" . $color;
//        var_dump($newName);
    } else if ($prod["description"] == "" && $prod["name"] == "") {
        $newName = $prod["code_product"];
//        var_dump("pene");
//        var_dump($prod["name"]);
//        echo "no desc\n";
//        var_dump($prod);
//        echo "new name " . $newName . "\n";
    } else {
        $color = count(explode(",", $prod["color"])) <= 1 ? $prod["color"] : "multicolor";
        $newName = $prod["name"] . "-" . $color;
    }

    return $newName;
}

/**
 * @param $prod
 * @return mixed
 */
function renameCode($prod)
{
    if ($prod["code"] == "") {
        $newCode = explode("-", $prod["code_product"])[count(explode("-", $prod["code_product"]))];
    } else {
        $newCode = $prod["code"];
    }
    return $newCode;
}

/**
 * @param $prod
 * @param $url
 * @param $sup
 * @param $prod_obj
 * @return void
 */
function validateImgs($prod, $url, $sup, $prod_obj)
{
    $product_code = $prod["code"];
    $img = "../../../img/product/" . explode(".", explode("/", $prod["img"])[1])[0] . ".jpg";

    foreach ($sup as $supplier) {
        if ((filesize($img) / 1000) < 1) {
            var_dump($img);

            switch ($supplier["supplier_name"]) {
                case "Macma":
                    if ($supplier["supplier_code"] == $prod["supplier"]) {
                        $file = upLoadIMG(
                            $supplier['supplier_api'],
                            $product_code,
                            "../../../img/product/",
                            $prod["color"],
                            0,
                            $supplier["supplier_id"],
                            explode(".", explode("/", $prod["img"])[1])[0],
                            $img
                        );
                        $prod_obj->updateImgPro($prod["product_id"], $file);
                    }

                    break;
                case "Blestar":
                    if ($supplier["supplier_code"] == $prod["supplier"]) {
                        $file = upLoadIMG(
                            $supplier['supplier_api'],
                            $product_code,
                            "../../../img/product/",
                            $prod["color"],
                            0,
                            $supplier["supplier_id"],
                            explode(".", explode("/", $prod["img"])[1])[0],
                            $img
                        );
                        $prod_obj->updateImgPro($prod["product_id"], $file);
                    }
                    break;
                case "Doblevela":
                    if ($supplier["supplier_code"] == $prod["supplier"]) {
                        $file = upLoadIMG(
                            $supplier['supplier_api'],
                            $product_code,
                            "../../../img/product/",
                            $prod["color"],
                            0,
                            $supplier["supplier_id"],
                            explode(".", explode("/", $prod["img"])[1])[0],
                            $img
                        );
                        $prod_obj->updateImgPro($prod["product_id"], $file);
                    }
                    break;
                case "G4":
                    break;
                case "4Promotional":
                    break;
                case "PROMOSOLUCIONES":
                    break;
                case "PROMOOPCION":
                    break;
                case "SUNLINE":
                    break;
                case "Impressline":
                    break;
                case "CAPSA":
                    break;
                case "MAXY":
                    break;
                case "Innova":
                    break;
                case "CDO":
                    break;
                case "GANE":
                    break;

            }

        }
    }

}

/**
 * @param $urlIMG
 * @param $code
 * @param $dir
 * @param $color
 * @param $try
 * @param $type
 * @param $product_name
 * @param $storedImg
 * @return string
 */
function upLoadIMG($urlIMG, $code, $dir, $color, $try, $type, $product_name, $storedImg)
{
    $product_filename = null;
//    var_dump(!strpos(@get_headers($urlIMG . $code . '.jpg')[0],'400 Bad Request'));
    //    var_dump($urlIMG . $code . '.jpg');
    if (!strpos(@get_headers($urlIMG . $code . '.jpg')[0], "404") && !strpos(@get_headers($urlIMG . $code . '.jpg')[0], '400 Bad Request')) {

        switch ($type) {
            case 1:
                $product_filename = $product_name . '.jpg'; //Este es el nombre que se almacenará en la BD

                $ch = curl_init($urlIMG . $code . '.jpg');
                break;
            case 2:
                $product_filename = $color . '-' . $code . '.jpg'; //Este es el nombre que se almacenará en la BD
                $ch = curl_init($urlIMG . $product_filename); //Esta es la url de la imagen a descargar
                break;
            case 3:
                $product_filename = $color . '.jpg'; //Este es el nombre que se almacenará en la BD
                $ch = curl_init($urlIMG);
                break;
            case 6:
            case 10:
            case 4:
                $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
                $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
                break;
            case 5:
                $product_filename = str_replace(' ', '-', $code . '.jpg'); //Este es el nombre que se almacenará en la BD
                $ch = curl_init(str_replace(' ', '%20', $urlIMG)); //Esta es la url de la imagen a descargar
                break;
        }
        $fp = fopen($dir . $product_filename, 'wb'); // Aqui se guarda la imagen

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $newFileName = "webp/" . explode(".", $product_filename)[0] . ".webp";
        $img = imagecreatefromjpeg($dir . $product_filename);
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        imagewebp($img, $dir . "/" . $newFileName, 80);
        imagedestroy($img);
    } else {

        unlink($storedImg);
        unlink("../../img/product/webp/" . explode(".", explode("/", $storedImg)[5])[0] . ".webp");
        $newFileName = "../../img/relleno-art.webp";
    }

    return $newFileName;
}

/**
 * @param $urlIMG
 * @param $code
 * @param $dir
 * @param $color
 * @param $try
 * @param $type
 * @param $product_name
 * @return string
 */
function upLoadSlide($urlIMG, $code, $dir, $color, $try, $type, $product_name)
{
    switch ($type) {
        case 1:
            $product_filename = $product_name . '-' . $color . '.jpg';
            $ch = curl_init($urlIMG . $code . '-' . $color . '.jpg');
            break;
        case 2:
            $product_filename = $color . '-' . $code . '.jpg';
            $ch = curl_init($urlIMG . $product_filename); //Esta es la url de la imagen a descargar
            break;
        case 3:
            $product_filename = str_replace(' ', '-', $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 4:
            $product_filename = str_replace(' ', '-', $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 5:
            $product_filename = str_replace(' ', '-', $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 6:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            //            dump($urlIMG . "---" . $code . "---" . $dir . "---" . $color . "---" . $try . "---" . $type . "---" . $product_name);
            break;
        case 10:
            $product_filename =
                explode('.', explode('/', $urlIMG)[3])[0] . '.jpg'; //Este es el nombre que se almacenará en la BD
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

    $newFileName = "webp/" . explode(".", $product_filename)[0] . ".webp";
    $img = imagecreatefromjpeg($dir . $product_filename);
    imagepalettetotruecolor($img);
    imagealphablending($img, true);
    imagesavealpha($img, true);
    imagewebp($img, $dir . "/" . $newFileName, 80);
    imagedestroy($img);

    return $newFileName;
}

/**
 * @param $PROD
 * @param $CATES
 * @return array
 */
function getDups($PROD, $CATES)
{
    /*
 SELECT cat_id, cat_name, COUNT(cat_name)
FROM tbl_category
GROUP BY cat_name
HAVING COUNT(cat_name) > 1
TODO: make this a method on category
 */
    $cateName = array();
    $duplicated_cats = $PROD->idkfa();
    foreach ($duplicated_cats as $cock) {
        $repeated = $cock["COUNT(cat_name)"];
        $name = $cock["cat_name"];
        $cate_id = $CATES->getCategory($name);
        $id_arr = array();
        foreach ($cate_id as $item) {
            $id_arr[] = $item["cat_id"];
        }
        sort($id_arr);
        $temp = [
            "name" => $name,
            "count" => $repeated,
            "ids" => $id_arr
        ];
        $cateName[] = $temp;


//        for ($i = 0; $i < strval($repeated); $i++) {
////            var_dump($cate_id);
//
//        }
    }

    return $cateName;
}

/**
 * @param $PROD
 * @param string $prod
 * @param string $newName
 * @param string $newProdCode
 * @param string $newCode
 * @param string $newUrl
 * @return int|void
 */
function updateProduct($PROD, $prod, $newName, $newProdCode, $newCode, $newUrl)
{
    if (!$PROD->updateNamePro($prod["product_id"], $newName)) {
        echo "error name";
        die();
    }
    if (!$PROD->updateCodeProduct($prod["product_id"], $newProdCode)) {
        echo "error codrproduct";
    }
    if (!$PROD->updateCode($prod["product_id"], $newCode)) {
        echo "error code";
    }
    if (!$PROD->updateUrl($prod["product_id"], $newUrl)) {
        echo "error url";
    }

    return 1;
}

/**
 * @param $url
 * @return bool
 */
function url_exists($url)
{
    $result = false;
    $url = filter_var($url, FILTER_VALIDATE_URL);

    /* Open curl connection */
    $handle = curl_init($url);

    /* Set curl parameter */
    curl_setopt_array($handle, array(
        CURLOPT_FOLLOWLOCATION => TRUE,     // we need the last redirected url
        CURLOPT_NOBODY => TRUE,             // we don't need body
        CURLOPT_HEADER => FALSE,            // we don't need headers
        CURLOPT_RETURNTRANSFER => FALSE,    // we don't need return transfer
        CURLOPT_SSL_VERIFYHOST => FALSE,    // we don't need verify host
        CURLOPT_SSL_VERIFYPEER => FALSE     // we don't need verify peer
    ));

    /* Get the HTML or whatever is linked in $url. */
    $response = curl_exec($handle);

    $httpCode = curl_getinfo($handle, CURLINFO_EFFECTIVE_URL);  // Try to get the last url
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);      // Get http status from last url

    /* Check for 200 (file is found). */
    if ($httpCode == 200) {
//        var_dump($response);
        $result = true;
    }

    /* Close curl connection */
    curl_close($handle);

    return $result;

}

/**
 * @param string $url
 * @return bool
 */
function isValidUrl(string $url): bool
{
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return (404 !== $httpCode);
}

function prodDepen($PROD, $CATES)
{
    $cateName = array();
    $duplicated_prods = $PROD->idkfap();
    foreach ($duplicated_prods as $cock) {


        $repeated = $cock["COUNT(name)"];
        $name = $cock["name"];
        $names = $PROD->getAllByName($name);

        if ($cock["supplier"] == 1) {
            $names = $PROD->getAllByCode($cock["code_product"]);
        }
        $parent = array();
        $children = array();

        for ($i = 0; $i < count($names); $i++) {
            if ($i == 0) {
                $parent[] = $names[$i];
            } else {
                $children[$i] = $names[$i];
                if ($children[$i]["product_depen"] == 0) {
                    $children[$i]["product_depen"] = $parent[0]["product_id"];
                }
            }
            $id_arr[$i] = $names[$i]["product_id"];
        }
        sort($id_arr);
//        var_dump($id_arr);
        $temp = [
            "name" => $name,
            "count" => $repeated,
            "parent" => $parent,
            "children" => $children,
            "ids" => $id_arr
        ];
        $cateName[] = $temp;


//        for ($i = 0; $i < strval($repeated); $i++) {
//            var_dump($cate_id);
//        }
    }
//    var_dump($cateName);
//    die();
    return $cateName;
}

function getSmolCats($PROD, $CATES)
{

    $cateName = array();
    $smol_cats = $CATES->smolCats();

    return $smol_cats;
}
//#endregion
