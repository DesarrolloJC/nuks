<?php
session_start();
header('Content-Type: text/html');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);

$ID = $_GET['id'];

$SQL = "SELECT * FROM tbl_category WHERE cat_id = '$ID' ";

$CATE = $CATES->CONN->Query($SQL);

$CATE_ID = $CATE[0]["cat_id"];

$SQL = "SELECT * FROM tbl_product WHERE category = '$CATE_ID'";

$CHILD_PROD = $CATES->CONN->Query($SQL);

echo json_encode($CHILD_PROD);