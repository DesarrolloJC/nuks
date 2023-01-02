<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/rol.class.php';

$DB = new DBConnection;
$ROL = new Rol($DB);

$data = $ROL->options();
//echo json_encode($data);
$RES = '';
foreach($data as $d){
    $RES .= "<option value='{$d['id_role']}'>{$d['role']}</option>";
}
echo json_encode($RES);
?>