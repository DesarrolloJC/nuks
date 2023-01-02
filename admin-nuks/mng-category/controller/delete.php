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

$LISTO = array('success' => true, 'title' => '¡Exito!', 'msg' => 'Eliminado correctamente.', 'class' => 'success');
$ERRGN = array('success' => false, 'title' => '¡Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');
$ERRCH = array('success' => false, 'title' => '¡No se puede eliminar!', 'msg' => $C['pc_nombre'] . ' contiene productos o categorias existentes.', 'class' => 'warning');

$CAT_PRODS = $CATES->getProducts($C['cat_id']);
$CAT_DEPENDENCY = $CATES->getDependency($C['cat_id']);
$CAT_PARENT = $CATES->CheckParent($CAT_DEPENDENCY[0]["cat_depen"])[0]["cat_id"];

$CHILDS = $CATES->CheckChilds($ID);

if ($CHILDS > 0) {
    echo json_encode($ERRCH);
    die();
}

if ($CATES->delete($ID)) {
    // echo $CATES->RecorrerDelete($C['pc_orden'], $C['pc_depende']);
    // die();
    echo json_encode($LISTO);
} else {
    echo json_encode($ERRGN);
    die();
}