<?php
header('Content-Type: application/json');
session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/supplier.class.php';

$DB = new DBConnection;
$suppliers = new Supplier($DB);

$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Proveedor agregado correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#NwSup").modal("hide");');

$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$_valores['supplier_code'] = $_POST['code'];
$_valores['supplier_name'] = $_POST['name'];
$_valores['supplier_website'] = $_POST['site'];
$_valores['supplier_api'] = $_POST['api'];

if ($suppliers->insert($_valores)) {
    echo (json_encode($hecho));
} else {
    echo json_encode($error);
}
