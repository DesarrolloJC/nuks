<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/users.class.php';
require_once '../model/rol.class.php';

$DB = new DBConnection;
$users = new User($DB);
$ROL = new Rol($DB);

$data = $users->getById($_GET['id']);

$roles = $ROL->getAll();
$opts = '';
foreach($roles as $r){
    if($r['id_role'] == $data['user_role']){
        $opts .= "<option value='{$r['id_role']}' selected>{$r['role']}</option>";
    }else{
        $opts .= "<option value='{$r['id_role']}'>{$r['role']}</option>";
    }
}
$data['user_role'] = $opts;

echo json_encode($data);
?>