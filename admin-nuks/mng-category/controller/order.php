<?php
session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
$DB = new DBConnection;

require_once '../model/category.class.php';
$CATES = new Category($DB);

$ID = $_POST['id'];
$PF = $_POST['pos'];

$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Actualizado correctamente.', 'class' => 'success', "final" => "tabla.ajax.reload();tabla2.ajax.reload();tabla3.ajax.reload();$('#orden').modal('hide');");
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');
$nulo = array('success' => true, 'title' => 'Nada por hacer!', 'msg' => 'La posición no ha cambiado.', 'class' => 'info');

$P = $CATES->getById($ID);

if ($PF == $P['cat_order']) {
    echo json_encode($nulo);
    die;
} elseif ($PF < $P['cat_order']) {
    $CATES->RecorrerLugarUP($PF, $P['cat_order'], $P['cat_depen']);
} else {
    $CATES->RecorrerLugarDOWN($PF, $P['cat_order'], $P['cat_depen']);
}

$_UPDT['cat_order'] = $PF;
if ($CATES->update($_UPDT, $ID)) echo json_encode($hecho);
else echo json_encode($error);
