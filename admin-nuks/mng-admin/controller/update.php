<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/users.class.php';

$DB = new DBConnection;
$users = new User($DB);
$uer = $users->getById($_POST["id"]);

$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Informacion actualizada correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#EdUs").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$_valores['user_name'] = $_POST['name'] ? $_POST['name'] : $uer["user_name"];
$_valores['user_lastname'] = $_POST['apellido'] ? $_POST["apellido"] : $uer["user_lastname"];
$_valores['user_email'] = $_POST['sesion'] ? $_POST["sesion"] : $uer["user_email"];
$_valores['user_pass'] = $_POST['contra'] ? md5($_POST['contra']) : $uer["user_pass"];
$_valores['user_role'] = $_POST['role'] ? $_POST["role"] : $uer["user_role"];

if ($users->update($_valores, $_POST['id'])) echo json_encode($hecho);
else echo json_encode($error);
