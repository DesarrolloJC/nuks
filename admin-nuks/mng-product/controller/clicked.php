<?php

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


$prod_id = $_POST["id"];

$clickedProduct = $PROD->getById($prod_id);
$clickedProduct["clicks"] += 1;

if ($PROD->update($clickedProduct, $clickedProduct['product_id'])) {
    echo "clicked";
} else {
    echo "error";

}
