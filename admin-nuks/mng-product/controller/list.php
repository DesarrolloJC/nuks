<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';

$DB = new DBConnection;
$PROD = new Product($DB);

$data = $PROD->getAll();
$nodo = array();

$DIR = '../../img/product/';

$WHERE = "";
$JOIN = "";
$DRAW = $_GET['draw'];
$START = $_GET['start'];
$ROWS = $_GET['length'];
$ICOL = $_GET['order'][0]['column'];
$SORT = $_GET['columns'][$ICOL]['name'];
$DIR = $_GET['order'][0]['dir'];
$SEARCH = $_GET['search']['value'];

if ($SEARCH != null && $SEARCH != "") {
    $WHERE .= " (name LIKE '%{$SEARCH}%' OR code_product LIKE '%{$SEARCH}%') ";
} else {
    $WHERE .= " 1 ";
}

$ORDER = " {$SORT} {$DIR} ";
$LIMIT = " {$START},{$ROWS} ";

//Total de registros en la tabla
$TR = $PROD->getTotalRegistros();

//Total de registros filtrados

$TRF = $PROD->getNumFiltrados($JOIN, $WHERE);

//Pagina de registros filtrados
$RESULTADOS = $PROD->getPaginaFiltrados($JOIN, $WHERE, $ORDER, $LIMIT);

foreach ($RESULTADOS as $value) {

//    var_dump($value);
//    die();

    $options = '<span>';
    $options .= '<a href="./edit.php?id=' . $value['product_id'] . '"><i class="fas fa-pen pre_edit-sm"></i></a>&nbsp;&nbsp;';
    $options .= '<i title="Eliminar Producto" class="fas fa-trash-alt pre_erase-sm pre_eraseFN" data-pro="' . $value['product_id'] . '"></i>';
    $options .= '</span>';


//    if (is_file("../../img/product/" . $value['img']) && (filesize("../../img/product" . $value['img']) / 1000) < 1) {
//        $img = '<img src="../../img/product/' . $value['img'] . '" alt="" style="width:150px;">';
//    } else {
//        $img = '<img src="../../img/relleno-art.png' . '" alt="" style="width:150px;">';
//    }
    $img = '<img src="../../img/product/' . $value['img'] . '" alt="" style="width:150px;">';

    $nodo[] = array(
        "nombre" => $value['name'],
        "imagen" => $img,
        "codigo" => $value['code_product'],
        "url" => $value["prov_website"],
        "opciones" => $options,
    );
}


$_RETURN = array(
    "draw" => intval($DRAW),
    "iTotalRecords" => $TR,
    "iTotalDisplayRecords" => $TRF,
    "aaData" => $nodo,
);
echo json_encode($_RETURN);
exit;
