<?php
session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';

$DB = new DBConnection;
$PRODUCTOS = new Product($DB);

$P = $PRODUCTOS->getById($_GET['id']);
$SLIDER = $PRODUCTOS->getSlider($P['product_id']);

$P["name"] = $PRODUCTOS->ReSatanizarTexto($P["name"]);
$P["description"] = $PRODUCTOS->ReSatanizarTexto($P["description"]);
$P["info"] = $PRODUCTOS->ReSatanizarTexto($P["info"]);

$_RETURN = array(
    "producto" => $P,
    "preview" => array(),
    "config" => array(),
    "categorias" => array(),
);

$_CATES = [];
$CAT = $P['category'];
array_push($_CATES, $CAT);

while ($CAT != 0) {
    $_CAT = $PRODUCTOS->getPadre($CAT);
    array_push($_CATES, $_CAT['cat_depen']);
    $CAT = $_CAT['cat_depen'];
}

array_pop($_CATES);
$_RETURN['categorias'] = array_reverse($_CATES);

foreach ($SLIDER as $S) {
//    if ($P['supplier'] == '30') {
//        $_RETURN["preview"][] = $S['slider_img'];
//        $_RETURN["config"][] = array("caption" => $S['slider_img'], "key" => $S['slider_prod_id']);
//    }
    $_RETURN["preview"][] = "../../img/product/" . $S['slider_img'];
    $_RETURN["config"][] = array("caption" => $S['slider_img'], "key" => $S['slider_prod_id']);
}
//var_dump($P);
//die();

echo json_encode($_RETURN);
