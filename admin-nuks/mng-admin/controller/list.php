<?php
session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/users.class.php';

$DB = new DBConnection;
$user = new User($DB);

$data = $user->getAll();
$nodo = array('data' => array());

foreach ($data as $value) {

    $options = '<span>';
    $options .= '<i title="Editar usuario" class="fas fa-pen pre_edit-sm" onclick="editar(' . $value['user_id'] . ');"></i>&nbsp;&nbsp;';
    $options .= '<i title="Eliminar usuario" class="fas fa-trash-alt pre_erase-sm pre_eraseFN" data-pro="' . $value['user_id'] . '"></i>';
    $options .= '</span>';
    $role = $value['user_role'];
    $sql = "SELECT role FROM tbl_roles WHERE id_role = '$role'";
    $res = $DB->Query($sql);
    $value['user_role'] = $res[0]['role'];

    $nodo['data'][] = array(
        $value['user_email'],
        $value['user_name'],
        $value['user_lastname'],
        $value['user_role'],
        $options
    );
}
echo json_encode($nodo);
