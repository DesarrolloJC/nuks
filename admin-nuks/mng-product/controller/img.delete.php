<?php

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/sliderProduct.class.php';

$DB = new DBConnection;
$SLIDER = new Slider($DB);

$DIR = "../../../img/product/";
$ID = $_POST['key'];
// echo "El \$ID es ".$ID;

$S = $SLIDER->getById($ID);
// var_dump($S);

if ($SLIDER->delete($ID)) {
    if (file_exists($DIR . $S['slider_img'])) unlink($DIR . $S['slider_img']);
    echo "1";
} else {
    echo "0";
}

?>
