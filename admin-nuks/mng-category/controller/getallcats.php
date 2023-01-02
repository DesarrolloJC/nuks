<?php
session_start();
header('Content-Type: text/html');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);

$SQL = "SELECT * FROM tbl_category WHERE 1";

$RES = $CATES->CONN->Query($SQL);

$RETURN = '<option value="0">Independiente</option>';
foreach ($RES as $R) {
    $RETURN .= '<option value="' . $R['cat_id'] . '">' . $R['cat_name'] . '</option>';
}

echo $RETURN;
