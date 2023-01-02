<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/supplier.class.php';

$DB = new DBConnection;
$suppliers = new Supplier($DB);

$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Informacion actualizada correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#EdSup").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$_valores['supplier_code'] = $_POST['code'];
$_valores['supplier_name'] = $_POST['name'];
$_valores['supplier_website'] = $_POST['site'];
$_valores['supplier_api'] = $_POST['api'];

if ($suppliers->update($_valores, $_POST['id'])) {
    echo json_encode($hecho);
} else {
    echo json_encode($error);
}
