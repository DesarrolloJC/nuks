<?php
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);

$ID = $_GET['id'];
$CAT = $CATES->getById($ID);

$CAT["cat_name"] = $CATES->ReSatanizarTexto($CAT["cat_name"]);

if ($CAT['cat_level'] == 2) {
    $PADRE = $CATES->getById($CAT['cat_depen']);
    $CAT['depende2'] = $PADRE['cat_depen'];
}

echo json_encode($CAT);