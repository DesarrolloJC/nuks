<?php
session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';
require_once '../model/sliderProduct.class.php';

$DB = new DBConnection;
$PRODUCTOS = new Product($DB);
$SLIDER = new Slider($DB);

$id = $_GET['id'];
$PRODUCTOS->delete($id);

$DIR = "../../../img/product/";
// echo "El \$ID es ".$ID;

$S = $SLIDER->getById($id);
// var_dump($S);

if ( $SLIDER->delete($id) ) {
  if ( file_exists($DIR.$S['slider_img'])) unlink($DIR.$S['slider_img']);
  echo "1";
} else {
  echo "0";
}

?>