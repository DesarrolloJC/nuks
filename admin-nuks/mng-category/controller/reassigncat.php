<?php
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../../mng-product/model/porduct.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);
$PROD = new Product($DB);

$ID = $_GET['id'];

$C = $CATES->getById($ID);

$LISTO = array('success' => true, 'title' => '¡Exito!', 'msg' => 'Reasignación exitosa.', 'class' => 'success');
$ERRGN = array('success' => false, 'title' => '¡Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$CAT_PRODS = $CATES->getProducts($C['cat_id']);

$CAT_DEPENDENCY = $CATES->getDependency($C['cat_id']);
$CAT_PARENT = $CATES->CheckParent($CAT_DEPENDENCY[0]["cat_depen"])[0]["cat_id"];
// var_dump($CAT_PARENT);

if (isset($_GET["newId"])) {
    $CAT_PARENT = intval($_GET["newId"]);
}

// var_dump($CAT_PARENT);
// die();

if ($PROD->updateCategory($C["cat_id"], $CAT_PARENT)) {
    // echo $CAT_PARENT;
    // echo $C["cat_id"];
    // echo $PROD->updateCategory($C["cat_id"], $CAT_PARENT);
    echo json_encode($LISTO);
} else {
    echo json_encode($ERRGN);
    die();
}