<?php

use League\Flysystem\Plugin\EmptyDir;

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

$DB = new DBConnection();
$PROD = new Product($DB);
$CAT = new Category($DB);
$SLIDER = new Slider($DB);
$SUPPLIERS = new Supplier($DB);
$PROGRESS = new progress($DB);

$suppliers = $SUPPLIERS->getAll();

$RESP = new ApiRestSoap();

$IMGURL = '../../../img/product/';

$hecho = [
    'success' => true,
    'title' => 'Exito!',
    'msg' => 'Productos agregados correctamente.',
    'class' => 'success',
    'final' => 'tabla.ajax.reload();$("#tblProduct").modal("hide");',
];
$PROV = $_GET['provUpd'];

$CODESINNOVA = '';

switch ($PROV) {
    case 1:
        $CODES = $PROD->ProductMacma($RESP->getProductsAllMacma());
        break;
    case 2:
        $CODES = $PROD->ProductBlestar($RESP->getProductsAllBlestar());
        break;
    case 3:
        $CODES = $RESP->getProductsAllDobleVela();
        break;
    case 4:
        $CODES = $PROD->ProductG4($RESP->getProductsAllG4());
        break;
    case 5:
        $CODES = $PROD->insertProduct4Promo();
        break;
    case 6:
        break;
    case 7:
        $supplier_api = $SUPPLIERS->getApi(
            $SUPPLIERS->getByName('PROMOOPCION')
        );
        break;
    case 8:
        break;
    case 9:
        for ($pag = 1; $pag <= 8; $pag++) {
            //$CODESINNOVA = array_merge($PROD->ProductInnovation($RESP->getProductsAllInnovation($pag)),$CODESINNOVA);
            if ($pag == 1) {
                $CODESINNOVA = $PROD->ProductInnovation(
                    $RESP->getProductsAllInnovation($pag)
                );
                //echo '<br>';
            } else {
                $CODESINNOVA = array_merge(
                    $PROD->ProductInnovation(
                        $RESP->getProductsAllInnovation($pag)
                    ),
                    $CODESINNOVA
                );
                //echo '<br>';
            }
            //echo 'Numero de pagina: '.$pag.'<br>';
        }
        $CODES = $CODESINNOVA;
        break;
    case 10:
        //        $CODES = ($RESP->getProductsAllInnovation(((array)json_decode($RESP->getActivePagesInnove()))["pages"]))["data"];
        $CODES = $RESP->getProductsAllInnovation(8)['data'];
        break;
    default:
        break;
}

$errArr = [];
$succArr = [];

$CODE_COUNT = count($CODES);
// $CODE_COUNT = 5;
$file = null;
$i = 0;

$lastInsertedProduct = null;

$error = [
    'success' => false,
    'title' => 'Error!',
    'msg' =>
        'Ocurrió un error al insertar, <br> i: ' .
        $i .
        ' ,  COUNT: ' .
        $CODE_COUNT .
        '<br>',
    'class' => 'error',
];
$COUNT_RES = [];

$flag = 0;

/*
LISTA DE APIS TERMINADAS
--- MACMA                                       [X]
--- Blestar                                     [X]
--- DOBLEVELA                                   [O]
--- G4                                          [X]
--- 4PROMO                                      [O]
--- PROMOSOLUCIONES                             [O]
--- Promoopcion                                 []
--- Sunline                                     []
--- Innovation                                  []
 */

//#region  APIS
try {
    switch ($PROV) {
        case 1:
            $PROV_ID = $SUPPLIERS->getByName('Macma');

            //#region Percentage init
            $PROG = generateProgObj($PROV_ID, 'Macma');
            //dump($PROG);

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            //#endregion

            $_SESSION['lastInsertedId'] = $DB->GetLastInsertID();

            $successCount = 0;

            $LAST_PRODUCT = returnPosition($DB, $PROD, $PROV_ID);
            if ($LAST_PRODUCT) {
                $i = $LAST_PRODUCT;
            } else {
                $i = 0;
            }

            for ($i; $i < $CODE_COUNT; $i++) {
                $PRODUCT = $PROD->insertProductMacma($CODES[$i]);
                $code_product = $suppliers[$PROV - 1]['supplier_code'] . '-' . $PRODUCT['code_product'];
                $exists = $PROD->getByName($code_product);

                if (($PRODUCT && !$exists && $PRODUCT['code_product'] != '') || ($PRODUCT && !$exists && $PRODUCT['name'] != '')) {
                    $name = $PROD->makeURL($PRODUCT['name']);
                    $CODE_PRODUCT = $PRODUCT['code_product'];

                    if (!file_exists($IMGURL . $name . '.jpg')) {
                        $file = upLoadIMG($suppliers[$PROV - 1]['supplier_api'], $CODE_PRODUCT, $IMGURL, $codeColor, 0, $PROV, $PRODUCT['name']);
                    }

                    $PRODUCT['code'] = $CODE_PRODUCT;
                    $PRODUCT['name'] = $PROD->SanitizarTexto($PRODUCT['name']);
                    $PRODUCT['url'] = $PROD->makeURL($PRODUCT['name']);
                    $PRODUCT['description'] = $PROD->SanitizarTexto($PRODUCT['description']);
                    $PRODUCT['img'] = $file;
                    $PRODUCT['code_product'] = $code_product;
                    $PRODUCT['product_depen'] = 0;
                    $PRODUCT['price'] = $PRODUCT['price'];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;
                    $PRODUCT['prov_website'] = $suppliers[$PROV - 1]['supplier_prod_url'] . $PRODUCT['code'];
                    $PRODUCT['supplier'] = $suppliers[$PROV - 1]['supplier_code'];

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
                    // TODO Aqui insertar el progreso en tbl_progress
                } else {
                    $PRODUCT['price'] = $PRODUCT['price'][0];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;

                    if ($PROD->update($PRODUCT, $exists["product_id"])) {
                        echo "actualizado";
                    }
                }
            }
            //Se valida si el ultimo producto importado es realmente el ultimo
            // errorHandler($error);

            if (
                ($i == $CODE_COUNT && $i == $successCount) ||
                ($i == $CODE_COUNT && $successCount > 0)
            ) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Productos agregados correctamente.',
                    'class' => 'success',
                    'final' => 'tabla.
                    ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } elseif ($i == $CODE_COUNT && $successCount == 0) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Los Productos ya han sido agregados.',
                    'class' => 'success',
                    'final' => 'tabla.
                    ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } else {
                echo json_encode(['retry']);
            }
            break;
        case 2:

            //https://blestar.net/mycavi/catalogue/getprovidersmy

            $PROV_ID = $SUPPLIERS->getByName('Blestar');

            //#region Percentage init
            $PROG = generateProgObj($PROV_ID, 'Blestar');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            //#endregion

            $_SESSION['lastInsertedId'] = $DB->GetLastInsertID();

            $successCount = 0;

            $LAST_PRODUCT = returnPosition($DB, $PROD, $PROV_ID);
            if ($LAST_PRODUCT) {
                $i = $LAST_PRODUCT;
            } else {
                $i = 0;
            }
            //    dump($CODE_COUNT."-".$LAST_PRODUCT."-".$i);

//            for ($i; $i < 15; $i++) {
            for ($i; $i < $CODE_COUNT; $i++) {

                $PRODUCT = $PROD->insertProductBlestar($CODES[$i]);
                $code_product = $suppliers[$PROV - 1]['supplier_code'] . '-' . $PRODUCT['code_product'];
                $exists = $PROD->getByName($code_product);


                $MATCHCAT = matchCategory($PRODUCT['description'], $CAT);
//                var_dump($MATCHCAT);

                if (!empty($MATCHCAT)) {
                    $PRODUCT['category'] = $MATCHCAT;
                } else {
                    $otroCat = $CAT->getByName("Otro");
                    $PRODUCT['category'] = $otroCat["cat_id"];
                }

//                 dump($PRODUCT["category"]);

                if (($PRODUCT && !$exists && $PRODUCT['code_product'] != '') || ($PRODUCT && !$exists && $PRODUCT['name'] != '')) {
                    $name = $PROD->makeURL($PRODUCT['name']);
                    $PRODUCT['name'] = $PROD->SanitizarTexto($PRODUCT['name']);
                    $PRODUCT['code'] = $PRODUCT['code_product'];
                    $PRODUCT['url'] = $PROD->makeURL($PRODUCT['name']);
                    $PRODUCT['description'] = $PROD->SanitizarTexto($PRODUCT['description']);

                    $PRODUCT_C = $PRODUCT['code_product'];
                    $PRODUCT['code_product'] = $suppliers[$PROV - 1]['supplier_code'] . '-' . $PRODUCT['code_product'];
                    $CODE_PRODUCT = $PRODUCT['code_product'];
                    $PRODUCT['prov_website'] =
                        $suppliers[$PROV - 1]['supplier_prod_url'] .
                        $PRODUCT['code'];

                    /* if (mk_folder($IMGURL."/".$suppliers[$PROV - 1]['supplier_name']."/") == "exists") {
                    $supplier_api_dir = $IMGURL.$suppliers[$PROV - 1]['supplier_name']."/";

                    }*/

                    if (!file_exists($IMGURL . $name . '.jpg')) {
                        $file = upLoadIMG(
                            $suppliers[$PROV - 1]['supplier_api'] .
                            $PRODUCT['code'] .
                            '.jpg' .
                            '&w=1500&h=1500&q=90&zc=2',
                            $PRODUCT_C,
                            $IMGURL,
                            $name,
                            0,
                            $PROV,
                            ''
                        );
                    }
                    $PRODUCT['img'] = $file;


                    $IDPROD = $PROD->searchProductID($PRODUCT['code_product']);
                    if (!empty($IDPRO['id'])) {
                        $PRODUCT['product_depen'] = $IDPRO['id'];
                    } else {
                        $PRODUCT['product_depen'] = 0;
                    }

                    $PRODUCT['info'] = $PROD->SanitizarTexto($PRODUCT['info']);
                    $PRODUCT['supplier'] =
                        $suppliers[$PROV - 1]['supplier_code'];

                    if ($PROD->insert($PRODUCT)) {
                        $ID = $DB->GetLastInsertID();

                        $CHILDS = $PROD->getCodesProductsBlestar($CODES[$i]);
                        $PRODUCT['product_depen'] = $ID;
                        $PRODUCT['name'] = $name;
                        $SL['prod_id'] = $ID;

                        if (count($CHILDS) > 0) {
                            $j = 1;
                            foreach ($CHILDS as $C) {
                                $codeColor = $C;
                                $PRODUCT['name'] =
                                    $name .
                                    '-' .
                                    $PROD->SanitizarTexto($codeColor);
                                $PRODUCT['color'] = $PROD->SanitizarTexto(
                                    $codeColor
                                );

                                $file = upLoadSlide(
                                    $suppliers[$PROV - 1]['supplier_api'] .
                                    $PRODUCT['code'] .
                                    '-' .
                                    $codeColor .
                                    '-' .
                                    $j .
                                    '.jpg&w=1500&h=1500&q=90&zc=2',
                                    $PRODUCT_C . '-' . $codeColor,
                                    $IMGURL,
                                    $name,
                                    0,
                                    $PROV,
                                    $PRODUCT['name']
                                );

                                //Slider section
                                if (file_exists($IMGURL . $file)) {
                                    $SL['slider_img'] = $file;
                                } else {
                                    $SL['slider_img'] =
                                        '../../../img/relleno-art.png';
                                }

                                $SL['slider_reference'] = $codeColor;
                                $SLIDER->insert($SL);
                                $SL['slider_img'] = '';

                                $PRODUCT['status'] = '0';
                                $j++;
                            }
                        }

                        if (!validateProd($PROD, $SLIDER, $DB)) {
                            $successCount++;
                        }
                    }
                    // TODO Aqui insertar el progreso en tbl_progress
                } else {
                    $PRODUCT['price'] = $PRODUCT['price'][0];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;

                    if ($PROD->update($PRODUCT, $exists["product_id"])) {
                        echo "actualizado";
                    }
                }
            }
            //Se valida si el ultimo producto importado es realmente el ultimo
            if ($i == $CODE_COUNT) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Productos agregados correctamente.',
                    'class' => 'success',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } else {
                // errorHandler(json_encode(array('success' => false, 'title' => 'Error', 'msg' => $i, 'class' => 'success', 'final' => 'tabla.ajax.reload(); $("#UpdPro").modal("hide")   ;')));
                echo json_encode([
                    'success' => false,
                    'title' => 'Error',
                    'msg' => 'Productos insertados: ' . $successCount,
                    'class' => 'error',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide")   ;',
                ]);
                // errorHandler($error);
            }
            break;
        case 3:
            //DobleVela
            $RES = jsonUtfHandler($CODES['GetExistenciaAllResult'], [
                'nombre',
                'existencias',
                'price',
                'pricelist',
                'apartado',
                'por_llegar_1',
                'fecha_aprox_de_llegada_1',
                'por_llegar_2',
                'fecha_aprox_de_llegada_2',
                'nombre_corto',
                'unidad_empaque',
                'medida_caja_master',
                'peso_caja',
                'descripcion',
                'material',
                'medida_producto',
                'tipo_empaque',
                'peso_producto',
                'tipo_impresion',
                'status',
                'familia',
                'subfamilia',
                'disponible_almacen_7',
                'disponible_almacen_8',
                'disponible_almacen_9',
                'disponible_almacen_10',
                'disponible_almacen_20',
                'disponible_almacen_24',
            ]);

            //            dump($RES);

            $CODE_COUNT = count($RES);

            $PROV_ID = $SUPPLIERS->getByName('DobleVela');

            //#region Percentage init
            $PROG = generateProgObj($PROV_ID, 'DobleVela');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            //#endregion

            $_SESSION['lastInsertedId'] = $DB->GetLastInsertID();

            $successCount = 0;

            $LAST_PRODUCT = returnPosition($DB, $PROD, $PROV_ID);
            if ($LAST_PRODUCT) {
                $i = $LAST_PRODUCT;
            } else {
                $i = 0;
            }

            //    dump($CODE_COUNT."-".$LAST_PRODUCT."-".$i);

            //TODO CHECK AGAIN THE thing with the act method in product I think

            for ($i; $i < $CODE_COUNT; $i++) {
//            for ($i; $i < 25; $i++) {
                if ($RES[$i] != '') {
                    //            var_dump($RES[$i]);
                    $CODE_PRODUCT = str_replace(' ', '', $RES[$i]['clave']);
                    $code_product =
                        $suppliers[$PROV - 1]['supplier_code'] .
                        '-' .
                        $CODE_PRODUCT;
                    $exists = $PROD->getByName($code_product);

                    if (
                        (!$exists && $RES[$i]['modelo'] != '') ||
                        (!$exists && $RES[$i]['nombre'] != '')
                    ) {
                        $name = $PROD->makeURL($RES[$i]['nombre']);
                        if (!file_exists($IMGURL . $name . '.jpg')) {
                            $file = upLoadIMG(
                                $suppliers[$PROV - 1]['supplier_website'] .
                                '/images/large/' .
                                $RES[$i]['modelo'] .
                                '_lrg.jpg',
                                $RES[$i]['clave'],
                                $IMGURL,
                                $suppliers[$PROV - 1]['supplier_code'] .
                                '-' .
                                $RES[$i]['modelo'],
                                0,
                                $PROV,
                                ''
                            );
                        }

                        //            dump($RES[$i]);

                        $PRODUCT['code'] = $RES[$i]['modelo'];
                        $PRODUCT['name'] = $RES[$i]['nombre'];
                        //                        var_dump($RES[$i]['nombre_corto']);
                        $PRODUCT['url'] = $PROD->makeURL(
                            $RES[$i]['nombre_corto']
                        );
                        $PRODUCT['description'] = $PROD->SanitizarTexto(
                            $RES[$i]['descripcion']
                        );

                        $PRODUCT['info'] = "Unidades por empaque";
                        $PRODUCT['info'] .= $RES[$i]["unidad_empaque"];
                        $PRODUCT['info'] .= "<br>";
                        $PRODUCT['info'] .= "Medidas ddel empaque";
                        $PRODUCT['info'] .= $RES[$i]["medida_caja_master"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['info'] .= "Peso del empaque";
                        $PRODUCT['info'] .= $RES[$i]["peso_caja"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['info'] .= "Material";
                        $PRODUCT['info'] .= $RES[$i]["material"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['info'] .= "Medidas del producto";
                        $PRODUCT['info'] .= $RES[$i]["medida_producto"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['info'] .= "Peso del producto";
                        $PRODUCT['info'] .= $RES[$i]["peso_producto"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['info'] .= "Tipo de empaque";
                        $PRODUCT['info'] .= $RES[$i]["tipo_empaque"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['info'] .= "Tipo de Impresion";
                        $PRODUCT['info'] .= $RES[$i]["tipo_impresion"];
                        $PRODUCT['info'] .= "<br>";

                        $PRODUCT['img'] = $file;
                        $PRODUCT['code_product'] =
                            $suppliers[$PROV - 1]['supplier_code'] .
                            '-' .
                            $RES[$i]['modelo'];

                        $PRODUCT['price'] = $RES[$i]['price'];
                        $PRODUCT['price_general'] = $RES[$i]['price'] * 1.35;
                        $PRODUCT['price_client'] = $RES[$i]['price'] * 1.3;
                        $PRODUCT['price_distributor_level_one'] =
                            $RES[$i]['price'] * 1.25;
                        $PRODUCT['price_distributor_level_two'] =
                            $RES[$i]['price'] * 1.23;
                        $PRODUCT['price_distributor_level_three'] =
                            $RES[$i]['price'] * 1.21;
                        $PRODUCT['prov_website'] =
                            $suppliers[$PROV - 1]['supplier_prod_url'] .
                            $RES[$i]['modelo'];
                        $lastInsertedProduct = $RES[$i]['modelo'];

                        $PRODUCT['status'] = 1;

                        $CATRES = $CAT->getCategory(
                            $PROD->SanitizarTexto($RES[$i]['familia'])
                        );

                        //#region categories
                        if (!$CATRES) {
                            //No se encontro la categoria
                            addCategory(
                                $RES[$i]['familia'],
                                $CAT
                            );
                            $CATRES = $CAT->getCategory(
                                $PROD->SanitizarTexto($RES[$i]['familia'])
                            );
                            $PRODUCT['category'] = $CATRES[0]['cat_id'];
                        } else {
                            $PRODUCT['category'] = $CATRES[0]['cat_id'];
                        }

                        //#endregion

                        $PRODUCT['supplier'] =
                            $suppliers[$PROV - 1]['supplier_code'];

                        // dump($PRODUCT);

                        $ID = $DB->GetLastInsertID(); //Se obtiene el ID del producto principal o padre
                        $lastInsertedProduct = $PROD->getById($ID);
                        if (
                            $lastInsertedProduct['description'] ==
                            $PRODUCT['description'] &&
                            $lastInsertedProduct['code_product'] ==
                            $PRODUCT['code_product']
                        ) {
                        } else {
                            $IDPROD = $PROD->searchProductID($PRODUCT['code_product']);
//                            var_dump($IDPROD);

                            if (isset($IDPROD)) {
                                $PRODUCT['product_depen'] = $IDPROD['id'];
                            } else {
                                $PRODUCT['product_depen'] = 0;
                            }

                            if ($PROD->insert($PRODUCT)) {
                                //Se inserta el producto principal y se agregan los productos hijos
                                //                        if (true) { //Se inserta el producto principal y se agregan los productos hijos

                                $_SESSION['lid'] =
                                    $lastInsertedProduct['code_product'];

                                $CHILDS = $RES[$i]['related']; //Se obtiene el numero de imagenes adicionales o productos hijo
                                if (count($CHILDS) > 0 && isset($CHILDS)) {
                                    //Si contiene productos hijo se agregaran en la base de datos dentro del FOREACH
                                    $SL = [];
                                    foreach ($CHILDS as $C) {
                                        $codeColor = '';
                                        $codeColor = explode(
                                            '-',
                                            str_replace(' ', '', $C['color'])
                                        );
                                        $PRODUCT['color'] = $PROD->SanitizarTexto(
                                            strtolower($codeColor[1])
                                        );
                                        //                                        dump($lastInsertedProduct);
                                        $SL['prod_id'] =
                                            $lastInsertedProduct['product_id'];

                                        $slide = upLoadSlide(
                                            $suppliers[$PROV - 1]['supplier_website'] .
                                            '/images/large/' .
                                            $RES[$i]['modelo'] .
                                            '_' .
                                            strtolower($codeColor[1]) .
                                            '_lrg.jpg',
                                            $RES[$i]['clave'],
                                            $IMGURL,
                                            $suppliers[$PROV - 1]['supplier_code'] .
                                            '-' .
                                            $RES[$i]['modelo'] .
                                            '-' .
                                            $PRODUCT['color'],
                                            0,
                                            $PROV,
                                            ''
                                        );
                                        //                                        dump($slide);
                                        //Slider section
                                        if (file_exists($IMGURL . $slide)) {
                                            $SL['slider_img'] = $slide;
                                        } else {
                                            $SL['slider_img'] =
                                                '../../../img/relleno-art.png';
                                        }
                                        $SL['slider_reference'] = $codeColor;
                                        $SLIDER->insert($SL);
                                        $SL['slider_img'] = '';
                                    }
                                }
                            }
                        }

                        /* if (mk_folder($IMGURL."/".$suppliers[$PROV - 1]['supplier_name']."/") == "exists") {
                    $supplier_api_dir = $IMGURL.$suppliers[$PROV - 1]['supplier_name']."/";

                    }*/
                    } else {
                        $PRODUCT['price'] = $PRODUCT['price'][0];
                        $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                        $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                        $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                        $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                        $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;

                        if ($PROD->update($PRODUCT, $exists["product_id"])) {
                            echo "actualizado";
                        }
                    }
                }
            }

            //Se valida si el ultimo producto importado es realmente el ultimo

            if (
                ($i == $CODE_COUNT && $successCount == $CODE_COUNT) ||
                ($i == $CODE_COUNT && $i > 0)
            ) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Productos agregados correctamente.',
                    'class' => 'success',
                    'final' => 'tabla .
                    ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } elseif ($i == $CODE_COUNT && $successCount == 0) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Los Productos ya han sido agregados.',
                    'class' => 'success',
                    'final' => 'tabla .
                    ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } elseif ($i == 0) {
                echo json_encode([
                    'success' => false,
                    'title' => 'Error!',
                    'msg' => 'Ocurrio un error, se insertaron: ' . $successCount . " productos",
                    'class' => 'success',
                    'final' => 'tabla .
                    ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            }
            break;
        case 4:
            //G4

            $PROV_ID = $SUPPLIERS->getByName('G4');

            //#region Percentage init
            $PROG = generateProgObj($PROV_ID, 'G4');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            //#endregion

            $_SESSION['lastInsertedId'] = $DB->GetLastInsertID();

            $successCount = 0;

            $LAST_PRODUCT = returnPosition($DB, $PROD, $PROV_ID);
            if ($LAST_PRODUCT) {
                $i = $LAST_PRODUCT;
            } else {
                $i = 0;
            }
            //    dump($CODE_COUNT . "-" . $LAST_PRODUCT . "-" . $i);

            for ($i; $i < $CODE_COUNT; $i++) {
                $PRODUCT = $PROD->insertProductG4($CODES[$i]);
//                var_dump($PRODUCT);
//                die();
                $code_product = $suppliers[$PROV - 1]['supplier_code'] . '-' . $PRODUCT['code_product'][0];
                $exists = $PROD->getByName($code_product);
//                $exists = false;

                if (!$exists) {
                    $name = $PROD->makeURL($PRODUCT['name'][0]);

                    $codeIMG = $PRODUCT['code_product'][0];
                    $PRODUCT['name'] = $PROD->SanitizarTexto($PRODUCT['name'][0]);

                    $PRODUCT['description'] = $PROD->SanitizarTexto($PRODUCT['description'][0]);
                    $PRODUCT['code'] = $PRODUCT['code_product'][0];
                    $PRODUCT['color'] = $PRODUCT['color'][0];
                    $PRODUCT['price'] = $PRODUCT['price'][0];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;
                    $PRODUCT['img'] = $PRODUCT['img'][0];
                    $PRODUCT['status'] = $PRODUCT['status'];

                    $PRODUCT['category'] = $PROD->SanitizarTexto($PRODUCT['category'][0]);
                    $PRODUCT['info'] = $PROD->SanitizarTexto($PRODUCT['info']);
                    $PRODUCT['prov_website'] = $suppliers[$PROV - 1]['supplier_prod_url'] . $PRODUCT['code_product'];

                    $PRODUCT['url'] = $PROD->makeURL($PRODUCT['code']);
                    $PRODUCT['code_product'] = $suppliers[$PROV - 1]['supplier_code'] . "-" . $PRODUCT['url'];


//                    var_dump($PRODUCT);

                    $lastInsertedProduct = $PRODUCT['code'];

                    if (!file_exists($IMGURL . $name . '-' . $CODES[$i] . '.jpg')) {
                        $file = upLoadIMG($PRODUCT['img'], $CODES[$i], $IMGURL, $name, 0, 4, '');
                    } else {
                        $file = $IMGURL . $name . '-' . $CODES[$i] . '.jpg';
                    }

                    $PRODUCT['img'] = $file;

                    $IDPRO = $PROD->searchProductName($PRODUCT['name']);
                    if (!empty($IDPRO)) {
                        $PRODUCT['product_depen'] = $IDPRO;
                        $PRODUCT['status'] = '0';
                    } else {
                        $PRODUCT['product_depen'] = 0;
                    }

                    $RES = $CAT->getCategory($PROD->SanitizarTexto($PRODUCT['category']));

                    if (count($RES) == 0) {
                        //No se encontro la categoria
                        addCategory($PROD->SanitizarTexto($PRODUCT['category']), $CAT);
                        $CATRES = $CAT->getCategory($PROD->SanitizarTexto($PRODUCT['category']));
                        $PRODUCT['category'] = $CATRES[0]['cat_id'];
                    } else {
                        $PRODUCT['category'] = $RES[0]['cat_id'];
                    }
                    $PRODUCT['supplier'] =
                        $suppliers[$PROV - 1]['supplier_code'];
//                    var_dump($PRODUCT);

                    if ($PROD->insert($PRODUCT)) { //Se inserta el producto principal y se agregan los productos hijos
//                    if ( /* $PROD->insert($PRODUCT) */true) { //Se inserta el producto principal y se agregan los productos hijos
                        $ID = $DB->GetLastInsertID(); //Se obtiene el ID del producto principal o padre
                        $lastProduct = $PROD->getById($ID);
                        $lastProductId = $lastProduct['code_product'];

                        $_SESSION['lid'] = $lastProductId;

                        $CHILDS = $PROD->getImgProductG4($CODES[$i]); //Se obtiene el numero de imagenes adicionales o productos hijo
                        if (count($CHILDS) > 0) {
                            //Si contiene productos hijo se agregaran en la base de datos dentro del FOREACH

                            $SL = [];
                            $j = 1;
                            foreach ($CHILDS as $C) {
                                $codeColor = $C[0];

                                $PRODUCT['color'] = $PROD->SanitizarTexto($codeColor);

                                $PRODUCT['name'] = $name;
                                $SL['prod_id'] = $ID;
                                // dump($PRODUCT);

                                $file = upLoadSlide($C, $CODES[$i] . '-' . $j, $IMGURL, $name, 0, 4, '');

                                if (file_exists($IMGURL . $file)) {
                                    $SL['slider_img'] = $file;
                                } else {
                                    $SL['slider_img'] = ' ../../../img / relleno-art . png';
                                }
                                $SL['slider_reference'] = $codeColor;
                                $SLIDER->insert($SL);
                                $SL['slider_img'] = '';

                                $j++;
                            }
                        }

                        $successCount++;
                    }
                } else {
                    $PRODUCT['price'] = $PRODUCT['price'][0];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;

                    if ($PROD->update($PRODUCT, $exists["product_id"])) {
                        echo "actualizado";
                    }
                }
            }
            //Se valida si el ultimo producto importado es realmente el ultimo
            if ($successCount == $CODE_COUNT) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Productos agregados correctamente . ',
                    'class' => 'success',
                    'final' =>
                        'tabla . ajax . reload(); $("#UpdPro") . modal("hide");',
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'title' => 'Error',
                    'msg' => 'Productos insertados: ' . $successCount,
                    'class' => 'error',
                    'final' =>
                        'tabla . ajax . reload(); $("#UpdPro") . modal("hide");',
                ]);
            }

            break;
        case 5:
            //4Promo
            $PRODUCT = [];
            $SLI = [];
            $PROV_ID = $SUPPLIERS->getByName('G4');

            //#region Percentage init
            $PROG = generateProgObj($PROV_ID, 'G4');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            //#endregion

            $_SESSION['lastInsertedId'] = $DB->GetLastInsertID();

            $successCount = 0;

            $LAST_PRODUCT = returnPosition(
                $DB,
                $PROD,
                $suppliers[$PROV - 1]['supplier_code']
            );
            if ($LAST_PRODUCT) {
                $i = $LAST_PRODUCT;
            } else {
                $i = 0;
            }
            //    dump($CODE_COUNT."-".$LAST_PRODUCT."-".$i);


//            for ($i; $i < 15; $i++) {
            for ($i; $i < $CODE_COUNT; $i++) {
                var_dump($CODES[$i]);
                $name = ($CODES[$i]['nombre_articulo']);
                $cod = $CODES[$i]['id_articulo'];
                $subCat = $CODES[$i]['sub_categoria'];
                $cat = $CODES[$i]['categoria'];

                $code_product = $suppliers[$PROV - 1]['supplier_code'] . '-' . $PRODUCT['code_product'][0];
                $exists = $PROD->getByName($code_product);
                $RES = $CAT->getCategory($PROD->SanitizarTexto($cat));
//                var_dump($RES);
//                die();

                if (!count($RES)) {
                    //No se encontro la categoria
                    addCategory($PROD->SanitizarTexto($cat), $CAT);
                    $CATRES = $CAT->getCategory($PROD->SanitizarTexto($cat));
                    $PRODUCT['category'] = $CATRES[0]['cat_id'];
                } else {
                    $PRODUCT['category'] = $RES[0]['cat_id'];
                }

//                if (count($RESUBCAT) == 0) {
//                    $namecategorySub = $PROD->SanitizarTexto($subCat);
//                    $urlcategorySub = $subCat;
//                    $idcategorySub = $RES[0]['cat_id'];
//                    addSubCategory($namecategorySub, $idcategorySub, $urlcategorySub, $CAT);
//                }

                if (!$exists) {
                    $codImg = $i . '-' . $cod;
                    $PRODUCT['name'] = $name;
                    $PRODUCT['code'] = $PROD->makeURL($suppliers[$PROV - 1]['supplier_code'] . "-" . $CODES[$i]['id_articulo']);

                    $PRODUCT['description'] = $PROD->SanitizarTexto($CODES[$i]['descripcion']);
                    $PRODUCT['info'] = 'Area de impresi&oacute;n: ' . $PROD->SanitizarTexto($CODES[$i]['area_impresion']) . ' <br>';
                    $PRODUCT['info'] .= 'Produndidad del articulo: ' . $PROD->SanitizarTexto($CODES[$i]['profundidad_articulo']) . ' <br>';
                    $PRODUCT['info'] .= 'Alto de caja: ' . $PROD->SanitizarTexto($CODES[$i]['alto_caja']) . ' <br>';
                    $PRODUCT['info'] .= 'Ancho caja: ' . $PROD->SanitizarTexto($CODES[$i]['ancho_caja']) . ' <br>';
                    $PRODUCT['info'] .= 'Capacidad: ' . $PROD->SanitizarTexto($CODES[$i]['capacidad']) . ' <br>';
                    $PRODUCT['info'] .= 'Metodos impresi&oacute;n: ' . $PROD->SanitizarTexto($CODES[$i]['metodos_impresion']) . ' <br>';
                    $PRODUCT['info'] .= 'Largo caja: ' . $PROD->SanitizarTexto($CODES[$i]['largo_caja']) . ' <br>';
                    $PRODUCT['info'] .= 'Ancho del producto: ' . $PROD->SanitizarTexto($CODES[$i]['medida_producto_ancho']) . ' <br>';
                    $PRODUCT['info'] .= 'alto del producto: ' . $PROD->SanitizarTexto($CODES[$i]['medida_producto_alto']) . ' <br>';
                    $PRODUCT['info'] .= 'Color: ' . $PROD->SanitizarTexto($CODES[$i]['web_color']) . ' <br>';
                    $PRODUCT['url'] = $name;
                    $PRODUCT['color'] = $CODES[$i]['color'];

                    $PRODUCT['price'] = $CODES[$i]['precio'];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;

                    $PRODUCT['prov_website'] = $suppliers[$PROV - 1]['supplier_prod_url'] . $PRODUCT['code'];

                    $PRODUCT['status'] = '1';

                    $PRODUCT['code_product'] = str_replace(' ', '-', $suppliers[$PROV - 1]['supplier_code'] . '-' . $i . '-' . $CODES[$i]['id_articulo']);
                    $PRODUCT['supplier'] = $suppliers[$PROV - 1]['supplier_code']; //Codigo de proveedor

                    $IDPROD = $PROD->searchProductName($PRODUCT['name']);

                    if ($IDPROD['id'] != 0) {
                        $PRODUCT['product_depen'] = $IDPROD['id'];
                        $PRODUCT['status'] = '0';
                    } else {
                        $PRODUCT['product_depen'] = 0;
                    }


                    if (count($CODES[$i]['imagenes']) > 0 && !file_exists($IMGURL . $name . '.jpg')) {
                        $PRODUCT['img'] = upLoadIMG($CODES[$i]['imagenes'][0]['url_imagen'], $cod . '-main', $IMGURL, '', 0, $PROV, '');
                    }
                    var_dump($PRODUCT);

                    if ($PROD->insert($PRODUCT)) {
//                    if (true) {
                        $ID = $DB->GetLastInsertID();
                        for ($con = 1; $con < count($CODES[$i]['imagenes']); $con++) {
                            $urlimg = $CODES[$i]['imagenes'][$con]['url_imagen'];
//                        if (true) {
                            if ($file = upLoadSlide($urlimg, $cod . "-sli-" . $con, $supplier_api_dir, "", 0, $PROV, "")) {
                                $_SLI['slider_img'] = $file;
                            } else {
                                $_SLI['slider_img'] = '../../../img/relleno-art.png';
                            }
                            $_SLI['prod_id'] = $ID;
                            //                        var_dump($_SLI);
                            //                        var_dump($urlimg);
                            $SLIDER->insert($_SLI);
                        }
                    }
                } else {
                    $PRODUCT['price'] = $PRODUCT['price'][0];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] = $PRODUCT['price'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] = $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] = $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] = $PRODUCT['price'] * 1.21;

                    if ($PROD->update($PRODUCT, $exists["product_id"])) {
                        echo "actualizado";
                    }
                }

            }
            //Se valida si el último producto importado es realmente el último
            if ($i == $CODE_COUNT) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Productos agregados correctamente.',
                    'class' => 'success',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } else {
                echo json_encode($error);
            }
            break;
        case 6:
            //Promo soluciones
            require_once '../../php/PHPExcel/Classes/PHPExcel.php';
            $FILE = $_GET['file'];
            $inputFileType = PHPExcel_IOFactory::identify($FILE);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($FILE);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $num = 0;
            $excel_array = [];

            $successCount = 0;

            $pos = 0;

            $related_array = [];

            $PROV_ID = $SUPPLIERS->getByName('PromoSoluciones');

            $PROG = generateProgObj($PROV_ID, 'PromoSoluciones');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            for ($row = 2; $row <= $highestRow; $row++) {
                $num++;
                //TODO get all the products that have the exact same SKU
                if ($sheet->getCell('B' . $row)->getValue() != '') {
                    $excel_array[] = [
                        'type' => unescape(
                            $sheet->getCell('A' . $row)->getValue()
                        ),
                        'sku' => unescape(
                            $sheet->getCell('B' . $row)->getValue()
                        ),
                        'name' => $sheet->getCell('C' . $row)->getValue()
                            ? str_replace(
                                ' ',
                                '',
                                unescape(
                                    $sheet->getCell('C' . $row)->getValue()
                                )
                            )
                            : str_replace(
                                ' ',
                                '',
                                unescape(
                                    $sheet->getCell('B' . $row)->getValue()
                                )
                            ),
                        'desc' => implode(
                            preg_split(
                                '/ \r\n|\r|\n/',
                                strip_tags(
                                    unescape(
                                        $sheet->getCell('D' . $row)->getValue()
                                    )
                                )
                            )
                        ),
                        'inv' => unescape(
                            $sheet->getCell('E' . $row)->getValue()
                        ),
                        'long' => unescape(
                            $sheet->getCell('F' . $row)->getValue()
                        ),
                        'ancho' => unescape(
                            $sheet->getCell('G' . $row)->getValue()
                        ),
                        'altura' => unescape(
                            $sheet->getCell('H' . $row)->getValue()
                        ),
                        'cat' => unescape(
                            $sheet->getCell('I' . $row)->getValue()
                        ),
                        'img' => explode(
                            ', ',
                            unescape($sheet->getCell('J' . $row)->getValue())
                        ),
                        'color' => explode(
                            ', ',
                            unescape($sheet->getCell('L' . $row)->getValue())
                        ),
                        'material' => unescape(
                            $sheet->getCell('N' . $row)->getValue()
                        ),
                        'related' => [],
                    ];
                }
            }

            //            getRelated(getNamesFromImg($excel_array));

            $arr2 = [];

            for ($i = 0; $i < count($excel_array[0]); $i++) {
                for ($j = 1; $j < count($excel_array[0]); $j++) {
                    if (
                        $excel_array[$j]['type'] == 'variation' &&
                        explode('-', $excel_array[$j]['sku'])[0] ==
                        $excel_array[$i]['sku']
                    ) {
                        $related_array[] = array_remove_keys($excel_array[$j], [
                            'type',
                            'name',
                            'desc',
                            'inv',
                            'long',
                            'ancho',
                            'altura',
                            'cat',
                            'material',
                            'related',
                        ]);
                        unset($excel_array[$j]);
                        $arr2 = array_values($excel_array);
                    }
                    $excel_array[$i]['related'] = $related_array;
                }
            }

            $PRODU = $arr2;
            dump(json_encode($PRODU));

            for ($i = 0; $i < count($PRODU[0]); $i++) {
                $code_product =
                    $suppliers[$PROV - 1]['supplier_code'] .
                    '-' .
                    $PRODU[$i]['sku'];

                $name = $PROD->makeURL(
                    explode(" \n", strip_tags($PRODU[$i]['desc']))[0]
                );
                $CODE_PRODUCT = $PRODU['sku'];

                $PRODUCT['code'] = $CODE_PRODUCT;
                $PRODUCT['name'] = $PROD->SanitizarTexto(
                    preg_split(
                        '/\r\n|\r|\n/',
                        strip_tags($PRODU[$i]['desc'])
                    )[0]
                );
                $PRODUCT['url'] = $PROD->makeURL(
                    preg_split(
                        '/\r\n|\r|\n/',
                        strip_tags($PRODU[$i]['desc'])
                    )[0]
                );
                $PRODUCT['description'] = $PROD->SanitizarTexto(
                    strip_tags($PRODU[$i]['desc'])
                );
                $PRODUCT['description'] .= '<br>';
                $PRODUCT['description'] .= 'Longitud: ' . $PRODU[$i]['long'];
                $PRODUCT['description'] .= '<br>';
                $PRODUCT['description'] .= 'Ancho: ' . $PRODU[$i]['ancho'];
                $PRODUCT['description'] .= '<br>';
                $PRODUCT['description'] .= 'Altura: ' . $PRODU[$i]['altura'];
                $PRODUCT['description'] .= '<br>';
                if (!file_exists($IMGURL . $name . '.jpg')) {
                    $file = upLoadIMG(
                        $PRODU[$i]['img'][0],
                        $code_product,
                        $IMGURL,
                        '',
                        0,
                        $PROV,
                        ''
                    );
                }
                $PRODUCT['img'] = $file;
                $PRODUCT['code_product'] = $code_product;
                $PRODUCT['product_depen'] = 0;
                $PRODUCT['price'] = $PRODU[$i]['price'];
                $PRODUCT['price_general'] = $PRODU[$i]['price'] * 1.35;
                $PRODUCT['price_client'] = $PRODU[$i]['price'] * 1.3;
                $PRODUCT['price_distributor_level_one'] =
                    $PRODU[$i]['price'] * 1.25;
                $PRODUCT['price_distributor_level_two'] =
                    $PRODU[$i]['price'] * 1.23;
                $PRODUCT['price_distributor_level_three'] =
                    $PRODU[$i]['price'] * 1.21;
                $PRODUCT['prov_website'] =
                    $suppliers[$PROV - 1]['supplier_website'] .
                    $PRODU[$i]['code'];
                $PRODUCT['supplier'] = $suppliers[$PROV - 1]['supplier_code'];

                $PRODUCT['status'] = 1;

                //                if (true) {
                if ($PROD->insert($PRODUCT)) {
                    $ID = $DB->GetLastInsertID(); //Se obtiene el ID del producto principal o padre
                    $lastProduct = $PROD->getById($ID);
                    $lastProductId = $lastProduct['code_product'];

                    $_SESSION['lid'] = $lastProductId;

                    $CHILDS = $PRODU[$i]['related']; //Se obtiene el numero de imagenes adicionales o productos hijo

                    if (count($CHILDS) > 0) {
                        //Si contiene productos hijo se agregaran en la base de datos dentro del FOREACH

                        $SL = [];
                        foreach ($CHILDS as $C) {
                            $codeColor = $C['color'][0];
//                            var_dump($C);

                            $PRODUCT['name'] = $name;
                            $PRODUCT['color'] = $PROD->SanitizarTexto(
                                $codeColor
                            );

                            $SL['prod_id'] = $ID;

                            $file = upLoadSlide(
                                $C['img'][0],
                                $PRODUCT['supplier'] . '-' . $C['sku'],
                                $IMGURL,
                                $codeColor,
                                0,
                                $PROV,
                                $C['img'][0]
                            );

                            //Slider section
                            if (file_exists($IMGURL . $file)) {
                                $SL['slider_img'] = $file;
                            } else {
                                $SL['slider_img'] =
                                    '../../../img/relleno-art.png';
                            }
                            $SL['slider_reference'] = $codeColor;

                            $SLIDER->insert($SL);
                            $SL['slider_img'] = '';
                        }
                    }

                    $successCount++;
                } else {
                    dump('IDK ');
                }
            }
            //            dump(json_encode($PRODU));
            if ($i == $CODE_COUNT) {
                echo json_encode([
                    'success' => true,
                    'title' => 'Exito!',
                    'msg' => 'Productos agregados correctamente.',
                    'class' => 'success',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide");',
                ]);
            } else {
                $error = json_encode([
                    'success' => false,
                    'title' => 'Error',
                    'msg' => 'Productos insertados: ' . $successCount,
                    'class' => 'error',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide")   ;',
                ]);
                echo $error;
            }
            unlink($FILE);

            break;
        case 7:
            //promoopcion
            require_once '../../php/PHPExcel/Classes/PHPExcel.php';
            $FILE = $_GET['file'];
            $inputFileType = PHPExcel_IOFactory::identify($FILE);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($FILE);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $num = 0;
            $excel_array = [];

            $successCount = 0;

            $pos = 0;

            $related_array = [];

            $PROV_ID = $SUPPLIERS->getByName('PROMOOPCION');

            $PROG = generateProgObj($PROV_ID, 'PROMOOPCION');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            $CODE_COUNT = 0;
            for ($row = 2; $row <= $highestRow; $row++) {
                $num++;
                //TODO get all the products that have the exact same SKU
                if ($sheet->getCell('B' . $row)->getValue() != '') {
                    $excel_array[] = [
                        'sku' => str_replace(
                            ' ',
                            '',
                            unescape($sheet->getCell('A' . $row)->getValue())
                        ),
                        'articulo principal' => unescape(
                            $sheet->getCell('B' . $row)->getValue()
                        ),
                        'categoria' => unescape(
                            $sheet->getCell('C' . $row)->getValue()
                        ),
                        'nombre' => unescape(
                            $sheet->getCell('D' . $row)->getValue()
                        ),
                        'desc' => unescape(
                            $sheet->getCell('E' . $row)->getValue()
                        ),
                        'color' => unescape(
                            $sheet->getCell('F' . $row)->getValue()
                        ),
                        'colors' => unescape(
                            $sheet->getCell('G' . $row)->getValue()
                        ),
                        'size' => unescape(
                            $sheet->getCell('H' . $row)->getValue()
                        ),
                        'material' => unescape(
                            $sheet->getCell('I' . $row)->getValue()
                        ),
                        'capacidad' => unescape(
                            $sheet->getCell('J' . $row)->getValue()
                        ),
                        'baterias' => unescape(
                            $sheet->getCell('K' . $row)->getValue()
                        ),
                        'impresion' => unescape(
                            $sheet->getCell('L' . $row)->getValue()
                        ),
                        'area_impresion' => unescape(
                            $sheet->getCell('M' . $row)->getValue()
                        ),
                        'nw' => unescape(
                            $sheet->getCell('N' . $row)->getValue()
                        ),
                        'gw' => unescape(
                            $sheet->getCell('O' . $row)->getValue()
                        ),
                        'altura' => unescape(
                            $sheet->getCell('P' . $row)->getValue()
                        ),
                        'ancho' => unescape(
                            $sheet->getCell('Q' . $row)->getValue()
                        ),
                        'long' => unescape(
                            $sheet->getCell('R' . $row)->getValue()
                        ),
                        'quantity' => unescape(
                            $sheet->getCell('S' . $row)->getValue()
                        ),
                        'img' => unescape(
                            $sheet->getCell('T' . $row)->getValue()
                        ),
                        'related' => $related_array,
                    ];
                }
            }

            //#region product OBJ
            $PRODU = $excel_array;
//            dump($PRODU);

            $CODE_COUNT = count($PRODU);

            for ($i = 0; $i < 1; $i++) {
                $code_product = $suppliers[$PROV - 1]['supplier_code'] . '-' . $PRODU[$i]['sku'];

                $name = $PROD->makeURL(explode(" \n", strip_tags($PRODU[$i]['desc']))[0]);
                $CODE_PRODUCT = $PRODU['sku'];

                $PRODUCT['code'] = $CODE_PRODUCT;
                $PRODUCT['name'] = $PROD->SanitizarTexto(preg_split('/\r\n|\r|\n/', strip_tags($PRODU[$i]['desc']))[0]);
                $PRODUCT['url'] = $PROD->makeURL(preg_split('/\r\n|\r|\n/', strip_tags($PRODU[$i]['desc']))[0]);
                $PRODUCT['description'] = $PROD->SanitizarTexto(strip_tags($PRODU[$i]['desc']));
                $PRODUCT['description'] .= '<br>';
                $PRODUCT['description'] .= 'Longitud: ' . $PRODU[$i]['long'];
                $PRODUCT['description'] .= '<br>';
                $PRODUCT['description'] .= 'Ancho: ' . $PRODU[$i]['ancho'];
                $PRODUCT['description'] .= '<br>';
                $PRODUCT['description'] .= 'Altura: ' . $PRODU[$i]['altura'];
                $PRODUCT['description'] .= '<br>';
                $file = upLoadIMG($PRODU[$i]['img'], $code_product, $IMGURL, '', 0, $PROV, '');
                $PRODUCT['img'] = $file;
                $PRODUCT['code_product'] = $code_product;
                $PRODUCT['product_depen'] = 0;
                $PRODUCT['price'] = 0;
                $PRODUCT['price_general'] = $PRODU[$i]['price'] * 1.35;
                $PRODUCT['price_client'] = $PRODU[$i]['price'] * 1.3;
                $PRODUCT['price_distributor_level_one'] =
                    $PRODU[$i]['price'] * 1.25;
                $PRODUCT['price_distributor_level_two'] =
                    $PRODU[$i]['price'] * 1.23;
                $PRODUCT['price_distributor_level_three'] =
                    $PRODU[$i]['price'] * 1.21;
                $PRODUCT['prov_website'] =
                    $suppliers[$PROV - 1]['supplier_website'] .
                    $PRODU[$i]['code'];
                $PRODUCT['supplier'] = $suppliers[$PROV - 1]['supplier_code'];

                $PRODUCT['status'] = 1;

                if ($PROD->insert($PRODUCT)) {
                    $successCount++;
                } else {
                    dump('IDK ');
                }
            }
            //#endregion

            if ($i == $CODE_COUNT) {
                $error = json_encode([
                    'success' => false,
                    'title' => 'Exito',
                    'msg' => 'Productos insertados: ' . $successCount,
                    'class' => 'warning',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide")   ;',
                ]);
                echo $error;
            } else {
                $error = json_encode([
                    'success' => false,
                    'title' => 'Exito',
                    'msg' => 'Productos insertados: ' . $successCount,
                    'class' => 'danger',
                    'final' =>
                        'tabla.ajax.reload(); $("#UpdPro").modal("hide")   ;',
                ]);
                echo $error;
            }
            unlink($FILE);

            break;
        case 8:
            //sunline
            break;
        case 9:
            //impressline
            break;
        case 10:
            //CAPSA
            break;
        case 11:
            //MAXY
            break;
        case 12:
            //CDO
            break;
        case 13:
            //innova
            $CODE_COUNT = count($CODES);

            $PROV_ID = $SUPPLIERS->getByName('Innova');

            $PROG = generateProgObj($PROV_ID, 'Innova');

            $RESPROG = $PROGRESS->saveProgress($PROG, $flag);

            $_SESSION['lastInsertedId'] = $DB->GetLastInsertID();

            $successCount = 0;

            $LAST_PRODUCT = returnPosition($DB, $PROD, $PROV_ID);
            if ($LAST_PRODUCT) {
                $i = $LAST_PRODUCT;
            } else {
                $i = 0;
            }

            for ($i; $i < $CODE_COUNT; $i++) {
                $PRODU = $CODES[$i];

                //                $PRODUCT = $PROD->insertProductMacma($CODES[$i]);
                $code_product =
                    $suppliers[$PROV - 1]['supplier_code'] .
                    '-' .
                    $PRODU['codigo'];
                $exists = $PROD->getByName($code_product);
                //                dump($PRODUCT);

                if ($PRODU && !$exists && $PRODU['codigo'] != '') {
                    $name = $PROD->makeURL($PRODU['name']);

                    $file = upLoadIMG(
                        $PRODU['imagen_principal'],
                        $PRODU['codigo'],
                        $IMGURL,
                        $PRODU['colores'][0]['codigo_color'],
                        0,
                        $PROV,
                        $PRODU['name']
                    );

                    $PRODUCT['code'] = $PRODU['codigo'];
                    $PRODUCT['name'] = $PROD->SanitizarTexto($PRODU['nombre']);
                    $PRODUCT['url'] = $PROD->makeURL($PRODU['nombre']);
                    $PRODUCT['description'] = $PRODU['descripcion'];
                    $PRODUCT['description'] .= '<br>Material: ';
                    $PRODUCT['description'] .= $PRODU['material'];
                    $PRODUCT['description'] .= '<br>Area de impresion: ';
                    $PRODUCT['description'] .= $PRODU['area_impresion'];
                    $PRODUCT['description'] .= '<br>Medidas: ';
                    $PRODUCT['description'] .= $PRODU['medidas_producto'];
                    $PRODUCT['description'] .= '<br>Peso del producto: ';
                    $PRODUCT['description'] .= $PRODU['peso_producto'];
                    $PRODUCT['description'] .= '<br>Cantidad por paquete: ';
                    $PRODUCT['description'] .= $PRODU['cantidad_por_paquete'];
                    $PRODUCT['description'] .= '<br>Medidas del paquete: ';
                    $PRODUCT['description'] .= $PRODU['medidas_paquete'];
                    $PRODUCT['description'] .= '<br>Peso del paquete: ';
                    $PRODUCT['description'] .= $PRODU['peso_paquete'];
                    $PRODUCT['description'] .= $PROD->SanitizarTexto(
                        $PRODUCT['description']
                    );

                    $PRODUCT['img'] = $file;
                    $PRODUCT['code_product'] = $code_product;
                    $PRODUCT['product_depen'] = 0;
                    $PRODUCT['price'] = $PRODU['lista_precios'][0]['precio'];
                    $PRODUCT['price_general'] = $PRODUCT['price'] * 1.35;
                    $PRODUCT['price_client'] =
                        $PRODU['lista_precios'][0]['mi_precio'] * 1.3;
                    $PRODUCT['price_distributor_level_one'] =
                        $PRODUCT['price'] * 1.25;
                    $PRODUCT['price_distributor_level_two'] =
                        $PRODUCT['price'] * 1.23;
                    $PRODUCT['price_distributor_level_three'] =
                        $PRODUCT['price'] * 1.21;
                    $PRODUCT['prov_website'] =
                        $suppliers[$PROV - 1]['supplier_website'] .
                        $PRODUCT['code'];
                    $PRODUCT['supplier'] =
                        $suppliers[$PROV - 1]['supplier_code'];
                    $categoryArr = explode(',', $PRODU['meta_keywords']);

                    $PRODUCT['status'] = '1';

                    $catFailCount = 0;

                    for ($i = 0; $i < count($categoryArr); $i++) {
                        $RES = $CAT->getCategory(
                            $PROD->SanitizarTexto($categoryArr[$i])
                        );
                        if (count($RES)) {
                            //No se encontro la categoria
                            $PRODUCT['category'] = $RES[0]['cat_id'];
                        }
                    }
                    if (!count($PRODUCT['category'])) {
                        addCategory(
                            $PROD->SanitizarTexto($PRODUCT['category']),
                            $CAT
                        );
                        $CATRES = $CAT->getCategory(
                            $PROD->SanitizarTexto($PRODUCT['category'])
                        );
                        $PRODUCT['category'] = $CATRES[0]['cat_id'];
                    }

                    if ($PROD->insert($PRODUCT)) {
                        //Se inserta el producto principal y se agregan los productos hijos
                        //                    if (true) { //Se inserta el producto principal y se agregan los productos hijos
                        $ID = $DB->GetLastInsertID(); //Se obtiene el ID del producto principal o padre
                        $lastProduct = $PROD->getById($ID);
                        $lastProductId = $lastProduct['code_product'];

                        //                        $_SESSION["lid"] = $lastProductId;

                        $CHILDS = $PRODU['imagenes_adicionales']; //Se obtiene el numero de imagenes adicionales o productos hijo
                        //                        var_dump($CHILDS);

                        if (count($CHILDS) > 0) {
                            //Si contiene productos hijo se agregaran en la base de datos dentro del FOREACH

                            for ($i = 0; $i < count($CHILDS); $i++) {
                                $SL = [];

                                $PRODUCT['color'] = '';
                                //                            $PRODUCT['color'] = $PROD->SanitizarTexto($codeColor);

                                $SL['prod_id'] = $ID;

                                $file = upLoadSlide(
                                    $PRODU['imagenes_adicionales'][$i],
                                    $PRODUCT['code_product'],
                                    $IMGURL,
                                    '',
                                    0,
                                    $PROV,
                                    $PRODUCT['name']
                                );

                                //Slider section
                                if (file_exists($IMGURL . $file)) {
                                    $SL['slider_img'] = $file;
                                } else {
                                    dump($file);
                                    $SL['slider_img'] =
                                        '../../../img/relleno-art.png';
                                }
                                $SL['slider_reference'] = explode(
                                    '-',
                                    explode('.', $file)[0]
                                )[1];
                                //                                dump($SL);
                                $SLIDER->insert($SL);
                                $SL['slider_img'] = '';
                            }
                        }
                    }
                    // TODO Aqui insertar el progreso en tbl_progress
                }
            }
            break;
        case 14:
            //GANE
            break;
    }
} catch (Throwable $exception) {
    dump($exception);
}

//#endregion

//se envia la URL de la imagen, el codigo de producto, el directorio donde se almacena y el nombre del archivo
function upLoadIMG($urlIMG, $code, $dir, $color, $try, $type, $product_name)
{

    if (!is_dir($dir)) {
        mkdir($dir);
    }

    if (!is_dir($dir . "/webp/")) {
        mkdir($dir . "/webp/");
    }

    $product_filename = null;
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
        case 4:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 5:
            $product_filename = str_replace(' ', '-', $code . '.jpg'); //Este es el nombre que se almacenará en la BD
            $ch = curl_init(str_replace(' ', '%20', $urlIMG)); //Esta es la url de la imagen a descargar
            break;
        case 6:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 7:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD

            if (get_http_code($urlIMG) == 301) {

                $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD

                $urlIMG = $urlIMG;

//                echo ($urlIMG);
//                die();
            }
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
        case 10:
            $product_filename = $code . '.jpg'; //Este es el nombre que se almacenará en la BD
            $ch = curl_init($urlIMG); //Esta es la url de la imagen a descargar
            break;
    }

//    dump($dir . "/webp/" . $newFileName);
    $fp = fopen($dir . $product_filename, 'wb'); // Aqui se guarda la imagen

    //    dump($urlIMG);

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

    return $newFileName;
}

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

function addCategory($Cat, $cat_obj)
{
    //Este metodo evaluara que la categoria entregada no exista, si esta no existe la agregara a la base de datos
    $_INS = [];
    $_INS['cat_name'] = $cat_obj->SanitizarTexto($Cat);
    $_INS['cat_url'] = $cat_obj->makeURL($Cat);
    $_INS['cat_order'] = $cat_obj->getNextPosition(0);
    $cat_obj->insert($_INS);
}

function addSubCategory($name, $idSub, $url, $cat_obj)
{
    $_INS = [];
    $_INS['cat_name'] = $name;
    $_INS['cat_depen'] = $idSub;
    $_INS['cat_url'] = $cat_obj->makeURL($url);
    $_INS['cat_order'] = $cat_obj->getNextPosition($idSub);
    $cat_obj->insert($_INS);
}

function matchCategory($TEXT, $CAT)
{
    $WORDS = explode(' ', $TEXT);
    foreach ($WORDS as $W) {
        $CATM = $CAT->getLikeCategory($W);
//        var_dump($CAT->SanitizarTexto($W));
//        var_dump($W);
//        var_dump(strtolower($W));
//        var_dump(strtoupper($W));
//        var_dump($CATM);
        if (count($CATM) > 0) {
            $IDCAT = $CATM[0]['cat_id'];
            return $IDCAT;
            break;
        } else if (count($CAT->getLikeCategory(strtolower($W))) > 0) {
            $CATM = $CAT->getCategory($CAT->SanitizarTexto($W));

            $IDCAT = $CATM[0]['cat_id'];
            return $IDCAT;
            break;
        } else if (count($CAT->getLikeCategory(strtoupper($W))) > 0) {
            $CATM = $CAT->getCategory($CAT->SanitizarTexto($W));
            $IDCAT = $CATM[0]['cat_id'];
            return $IDCAT;
            break;
        }
//        die();
    }
}

function errorHandler(
    $error,
    $position,
    $lastInsertedProduct,
    $file = '../../../logs/error.log'
)
{
    ($myfile = fopen($file, 'w')) or die('Unable to open file!');
    //        $txt = "response: " . json_encode($error);
    $txt = json_encode($error);
    fwrite($myfile, $txt);
    fclose($myfile);
    // echo json_encode($error);
}

function stripQuotes($text)
{
    return preg_replace('/(^[\"\']|[\"\']$)/', '', $text);
    //    return preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $text);
}

function generateOgUrl($url, $prod)
{
    var_dump($url, $prod);
    die();
}

function calcPercentDone($count, $total_count)
{
    return $percent = ($count * 100) / $total_count;

    // echo " < input type = 'text' id = 'test' value = '$percent' > ";
    // echo json_encode(array('success' => true, 'title' => 'Exito!', 'msg' => $percent, 'class' => 'info'));
}

function deleteNullProduct($id, $PRODUCTOS, $SLIDER)
{
    $PRODUCTOS->delete($id);

    $DIR = ' ../../../img/product/';
    // echo "El \$ID es ".$ID;

    $S = $SLIDER->getById($id);
    // var_dump($S);

    if ($SLIDER->delete($id)) {
        if (file_exists($DIR . $S['slider_img'])) {
            unlink($DIR . $S['slider_img']);
        }

        echo '1';
    } else {
        echo '0';
    }
}

function validateProd($PROD, $SLIDER, $DB)
{
    $lastProduct = $PROD->getLastInsertedProd();

    if ($lastProduct['code_product'] == null) {
        $lastProduct['code_product'] = $lastProduct['name'];
        // deleteNullProduct($lastProduct["product_id"], $PROD, $SLIDER);
        // errorHandler(json_encode(array('success' => false, 'title' => 'Error', 'msg' => 'El producto es nulo', 'class' => 'danger', 'final' => 'tabla.ajax.reload(); $("#UpdPro").modal("hide");')), $i, $lastProduct);

        // $DB->execute('KILL CONNECTION_ID()');
        // $DB = null;
    } elseif ($lastProduct['name'] == null) {
        // $lastProduct["name"]= $lastProduct["code_product"]
    }
    // echo json_encode(array('success' => false, 'title' => 'Error', 'msg' => 'El producto es nulo', 'class' => 'danger', 'final' => 'tabla.ajax.reload(); $("#UpdPro").modal("hide");')), $i, $lastProduct);
}

function dump($thing)
{
    var_dump($thing);
    die();
}

function returnPosition($DB, $PROD, $PROV)
{
    $COUNT_OTHER_SUPPLIERS = $PROD->countWhereDifferentSupplier($PROV);
    $allCounted = $PROD->countAll();
    return $allCounted - $COUNT_OTHER_SUPPLIERS;
}

function jsonUtfHandler($json, $keys_to_ignore = null, $col = 'modelo')
{
    $responseArray = json_decode(
        mb_convert_encoding($json, 'UTF-8', 'UTF-BMP'),
        true
    )['Resultado'];
    $relatedArray = [];
    for ($i = 0; $i < count($responseArray); $i++) {
        $responseArray[$i] = fixArrayKey($responseArray[$i]);
    }
    $sortedArray = sortBySubValue($responseArray, $col, true, false);
    $temp1 = [];
    $temp2 = [];
    $currentTry = 0;
    for ($i = 0; $i < count($sortedArray); $i++) {
        if ($sortedArray[$currentTry][$col] == $sortedArray[$i + 1][$col]) {
            $relatedArray[] = array_remove_keys(
                $sortedArray[$i + 1],
                $keys_to_ignore
            );
            $sortedArray[$i + 1] = '';
        } else {
            if (count($relatedArray) == 0) {
                $sortedArray[$currentTry]['related'] = '';
            } else {
                $sortedArray[$currentTry]['related'] = $relatedArray;
            }
            $relatedArray = [];
            $currentTry++;
        }
    }

    return array_filter($sortedArray, function ($var) {
        return $var !== null && $var !== false && $var !== '';
    });
    //    return $sortedArray;
}

function fixArrayKey(&$arr)
{
    $arr = array_combine(
        array_map(function ($str) {
            return str_replace(' ', '_', strtolower($str));
        }, array_keys($arr)),
        array_values($arr)
    );

    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            fixArrayKey($arr[$key]);
        }
    }
    return $arr;
}

function sortBySubValue($array, $value, $asc = true, $preserveKeys = false)
{
    if ($preserveKeys) {
        $c = [];
        if (is_object(reset($array))) {
            foreach ($array as $k => $v) {
                $b[$k] = strtolower($v->$value);
            }
        } else {
            foreach ($array as $k => $v) {
                $b[$k] = strtolower($v[$value]);
            }
        }
        $asc ? asort($b) : arsort($b);
        foreach ($b as $k => $v) {
            $c[$k] = $array[$k];
        }
        $array = $c;
    } else {
        if (is_object(reset($array))) {
            usort($array, function ($a, $b) use ($value, $asc) {
                return $a->{$value} == $b->{$value}
                    ? 0
                    : ($a->{$value} <=> $b->{$value}) * ($asc ? 1 : -1);
            });
        } else {
            usort($array, function ($a, $b) use ($value, $asc) {
                return $a[$value] == $b[$value]
                    ? 0
                    : ($a[$value] <=> $b[$value]) * ($asc ? 1 : -1);
            });
        }
    }

    return $array;
}

function array_remove_keys($array, $keys = [])
{
    // If array is empty or not an array at all, don't bother
    // doing anything else.
    if (empty($array) || !is_array($array)) {
        return $array;
    }

    // If $keys is a comma-separated list, convert to an array.
    if (is_string($keys)) {
        $keys = explode(',', $keys);
    }

    // At this point if $keys is not an array, we can't do anything with it.
    if (!is_array($keys)) {
        return $array;
    }

    // array_diff_key() expected an associative array.
    $assocKeys = [];
    foreach ($keys as $key) {
        $assocKeys[$key] = true;
    }

    return array_diff_key($array, $assocKeys);
}

function mk_folder($folder)
{
    if (is_dir($folder)) {
        return 'exists';
    } else {
        mkdir($folder);
        mk_folder($folder);
    }
}

function getNamesFromImg($arr)
{
    $return = [];
    for ($i = 0; $i < count($arr); $i++) {
        for ($j = 0; $j < count($arr[$i]); $j++) {
            $return[] = explode('_', basename($arr[$i]['img'][$j], '.jpg'))[0];
        }
    }
    return $return;
}

function getRelated($arr, $ogarr = null)
{
    $temp_prod = '';
    $rel = [];
    $idk = [];
    for ($i = 1; $i < count($arr); $i++) {
        $idgaf = count(explode('-', $arr[$i]));
        if ($idgaf == 0) {
            $temp_prod = $arr[$i];
        }

        echo $temp_prod;

        if ($temp_prod == $idgaf[$i]) {
            $idk[] = [$temp_prod => [$idgaf[$i]]];
        } else {
            $idk[] = 'nel';
        }
    }
    //    $rel[]
    return $ogarr;
}

function unescape($string)
{
    return preg_replace_callback(
        '/_x([0-9a-fA-F]{4})_/',
        function ($matches) {
            return iconv('UCS-2', 'UTF-8', hex2bin($matches[1]));
        },
        $string
    );
}

/**
 * @param $PROV_ID
 * @param $PROV
 * @return array
 */
function generateProgObj($PROV_ID, $PROV)
{
    $PROG['prog_percent'] = 0;
    $PROG['prog_lastinsertedid'] = '';
    $PROG['prog_lastproductname'] = '';
    $PROG['prog_lastproductprovcode'] = $PROV_ID;
    $PROG['prog_lastproductprov'] = $PROV;
    $PROG['prog_errmsg'] = '';
    $PROG['prog_sucmsg'] = '';
    $PROG['prog_dateaddedorfailed'] = '';

    return $PROG;
}

function get_http_code($url)
{
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    return $httpCode;
}
