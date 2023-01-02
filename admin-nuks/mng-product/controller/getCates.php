<?php
session_start();
header('Content-Type: text/html');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/porduct.class.php';
$PROD = new Product($DB);

if ($_GET['id'] == "") {
    echo "0";
    die;
}

$CATS = $PROD->getCates($_GET['id']);
$RET = "";

if (count($CATS) == 0) {
    $RET = "0";
} else {
    $RET .= '<option value="">Seleccione Categoria</option>';
    foreach ($CATS as $C) {
        if (isset($_GET['val']) && $_GET['val'] == $C['cat_id']) {
            $RET .= '<option value="' . $C['cat_id'] . '" selected>' . $C['cat_name'] . '</option>';
        } else {
            $RET .= '<option value="' . $C['cat_id'] . '">' . $C['cat_name'] . '</option>';
        }
    }
}

echo $RET;