<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/seo.class.php';

$DB = new DBConnection;
$SEO = new Seo($DB);

$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Información actualizada correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#EdSEO").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

$_valores['seo_keywords']    = $_POST['keywords'];
$_valores['seo_description'] = $_POST['description'];

if ($SEO->update($_valores, $_POST['id'])) echo json_encode($hecho);
else echo json_encode($error);
?>