<?php
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../../mng-category/model/category.class.php';
require_once '../../mng-product/model/porduct.class.php';
require_once '../../mng-supplier/model/supplier.class.php';

$DB = new DBConnection;

$CATES = new Category($DB);
$PROD = new Product($DB);
$SUP = new Supplier($DB);

$products = $PROD->getAll();

foreach ($products as $product) {
    $product["clicks"] = floor(rand(0, 1000));

    if ($PROD->update($product, $product["product_id"])) {
        echo "updated";
    }
}

