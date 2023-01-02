<?php
session_start();
header('Content-Type: text/html');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/porduct.class.php';
$PROD = new Product($DB);

if ($_GET['name'] == "") {
    echo "0";
    die;
}

$CAT = $PROD->getCateByName($_GET['name']);


echo $CAT[0]["cat_id"];
