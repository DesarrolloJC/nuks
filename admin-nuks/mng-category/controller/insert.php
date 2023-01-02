<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);

$LISTO = array('success' => true, 'title' => 'Exito!', 'msg' => 'Agregado correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();tabla2.ajax.reload();tabla3.ajax.reload();$("#nw-cat").modal("hide");');
$ERRGN = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');
$ERRFI = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error al subir archivo: ', 'class' => 'error');

$_INS = array();
$URL = $CATES->makeURL($_POST['nombre']);

$_INS['cat_name']  = $CATES->SanitizarTexto($_POST['nombre']);
$_INS['cat_url']    = $URL;

if ($_POST['subcat'] != 0) {
    $_INS['cat_depen'] = $_POST['subcat'];
    $_INS['cat_level'] = 2;
} else if ($_POST['cat'] != 0) {
    $_INS['cat_depen'] = $_POST['cat'];
    $_INS['cat_level'] = 1;
} else {
    $_INS['cat_depen'] = $_POST['cat'];
    $_INS['cat_level'] = 0;
}

$_INS['cat_order'] = $CATES->getNextPosition($_INS['cat_depen']);

if ($CATES->insert($_INS)) echo json_encode($LISTO);
else  echo json_encode($ERRGN);

exit;
