<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/home.class.php';

$DB = new DBConnection;
$homeSlider = new Home($DB);

$DIRIMG = '../../../assets/images/sliderHome/';
$ID = $_GET['id'];

$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Eliminado correctamente.', 'class' => 'success');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$IMG = $homeSlider->getById($ID);

if (unlink($DIRIMG . $IMG['slider_img'])) echo 'Se elimino la imagen';

if ($homeSlider->delete($ID)) echo json_encode($hecho);
else echo json_encode($error);
