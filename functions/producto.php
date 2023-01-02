<?php

$URL = $_GET['parametro'];
$PROD = $SERV->getProduct($URL);

//var_dump($PROD);
//die();
$TITLE = $PROD[0]['name'];
$PRODUCTS = $SERV->getProductsSimillar($TITLE, $PROD[0]["product_id"]);
if (empty($PRODUCTS[0]['name'])) {
    $PRODUCTS = $SERV->getProductsSimillarCat($PROD[0]['cat_id']);
}
$DESCRIPTION .= $TITLE . ' ' . $PROD[0]['description'] . ' ' . $PROD[0]['category'];
$KEYWORDS .= ',' . $TITLE . ',' . $PROD[0]['category'];
$SL = $SERV->getSliderProduct($PROD[0]["product_id"]);

?>
