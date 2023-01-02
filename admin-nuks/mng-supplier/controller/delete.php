<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/supplier.class.php';

$DB = new DBConnection;
$supplier = new Supplier($DB);

$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Proveedor eliminado correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#NwUs").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$id = $_GET['id'];
// $supplier->delete($id);

if ($supplier->delete($id)) {
    echo (json_encode($hecho));
} else {
    echo json_encode($error);
}