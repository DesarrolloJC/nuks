<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/home.class.php';

$DB = new DBConnection;
$homeSlider = new Home($DB);

$DIRIMG = '../../../assets/images/sliderHome/';
$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Slider agregado correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload(); $("#NwSl").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');
$ERRFI = array('error' => false, 'title' => 'Error al subir la imagen!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'warning');

$_valores['slider_name']    = $homeSlider->SanitizarTexto($_POST['name']);

if(empty($_POST['url'])){
    $_valores['slider_url'] = '#';
}else{
    $_valores['slider_url'] = $homeSlider->SanitizarTexto($_POST['url']);
}

if ($_FILES["imagen"]['error'] != 4) {
    $IMG = "art-promo-" .$_valores['slider_name']."-". $homeSlider->makeURLFILE($_FILES["imagen"]['name']);
    move_uploaded_file($_FILES["imagen"]['tmp_name'], $DIRIMG . $IMG);
    $_valores['slider_img'] = $IMG;
} else {
    $ERRFI['msg'] .= $homeSlider->getFileErrorMSG($_FILES["imagen"]['error']);
    echo json_encode($ERRFI);
    exit;
}

if ($homeSlider->insert($_valores)) echo (json_encode($hecho));
else echo json_encode($error);
