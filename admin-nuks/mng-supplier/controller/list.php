<?php
session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/supplier.class.php';

$DB = new DBConnection;
$suppliers = new Supplier($DB);

$data = $suppliers->getAll();
$nodo = array('data' => array());

foreach ($data as $value) {

    $options = '<span>';
    $options .= '<i title="Editar proveedor" class="fas fa-pen pre_edit-sm" onclick="editar(' . $value['supplier_id'] . ');"></i>&nbsp;&nbsp;';
    $options .= '<i title="Eliminar proveedor" class="fas fa-trash-alt pre_erase-sm pre_eraseFN" data-pro="' . $value['supplier_id'] . '"></i>';
    $options .= '</span>';

    $nodo['data'][] = array(
        $value['supplier_code'],
        $value['supplier_name'],
        $value['supplier_website'],
        $value['supplier_api'],
        $value['supplier_prod_url'],
        $value['supplier_prod_img'],
        $options,
    );
}
echo json_encode($nodo);
