<?php
session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';

$DB = new DBConnection;
$PRODUCTOS = new Product($DB);

$categorySelectPHP = $_GET['categorySelectPHP'];
$searchFieldPHP = $_GET['searchFieldPHP'];

$search = $PRODUCTOS->searchProd($categorySelectPHP, $searchFieldPHP);


if ($search > 0) {
    echo json_encode($search);
} else {
    echo json_encode("No hay productos");
}
//die();
