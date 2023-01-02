<?php
session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);

// Parametros del cliente
$WHERE = "";
$JOIN = "";
$DRAW = $_GET['draw'];
$START = $_GET['start'];
$ROWS = $_GET['length'];
$ICOL = $_GET['order'][0]['column'];
$SORT = $_GET['columns'][$ICOL]['name'];
$DIR = $_GET['order'][0]['dir'];
$SEARCH = $CATES->SanitizarTexto($_GET['search']['value']);
$NIVEL = $_GET['nivel'];

// Parametros de búsqueda
$WHERE = " cat_level = {$NIVEL} ";
if (isset($_GET['padre']) && $_GET['padre'] != 0) {
    $WHERE .= " AND cat_depen = {$_GET['padre']} ";
}
if ($SEARCH != null && $SEARCH != "") {
    $WHERE .= " AND cat_name LIKE '{$SEARCH}%' ";
}
$ORDER = " {$SORT} {$DIR} ";
$LIMIT = " {$START},{$ROWS} ";

//Total de registros en la tabla
$TR = $CATES->getTotalRegistros();

//Total de registros filtrados
$TRF = $CATES->getNumFiltrados($JOIN, $WHERE);

//Pagina de registros filtrados
$RESULTADOS = $CATES->getPaginaFiltrados($JOIN, $WHERE, $ORDER, $LIMIT);

$DATA = array();
foreach ($RESULTADOS as $R) {
    $OPC = "";
    $OPC .= '<i class="fas fa-exchange-alt reassign" data-id="' . $R['cat_id'] . '"></i>&nbsp;&nbsp;';

    if ($NIVEL < 2) {
        $OPC .= '<i class="fa fa-list-ul" data-nivel="' . $NIVEL . '" data-id="' . $R['cat_id'] . '"></i>&nbsp;&nbsp;';
    }

    $OPC .= '<i class="fas fa-pen pre_edit-sm edit" data-id="' . $R['cat_id'] . '"></i>&nbsp;&nbsp;';
    $OPC .= '<i class="fas fa-trash-alt del" data-id="' . $R['cat_id'] . '"></i>';

    $ORD = '<i class="fa fa-sort" data-id="' . $R['cat_id'] . '" data-orden="' . $R['cat_order'] . '"></i>&nbsp;&nbsp;';
    $ORD .= $R['cat_order'];

    $DATA[] = array(
        "orden" => $ORD,
        "nombre" => $R['cat_name'],
        "opciones" => $OPC,
    );
}

$_RETURN = array(
    "draw" => intval($DRAW),
    "iTotalRecords" => $TR,
    "iTotalDisplayRecords" => $TRF,
    "aaData" => $DATA,
);

echo json_encode($_RETURN);
exit;