<?php
header('Content-Type: application/json');
session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/users.class.php';

$DB = new DBConnection;
$users = new User($DB);

$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Usuario agregado correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#NwUs").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$_valores['user_name'] = $_POST['name'];
$_valores['user_lastname'] = $_POST['lastname'];
$_valores['user_email'] = $_POST['email'];
$_valores['user_pass'] = md5($_POST['pass']);
$_valores['user_role'] = $_POST["role"];

if ($users->insert($_valores)) {
    echo(json_encode($hecho));
} else {
    echo json_encode($error);
}
?>
