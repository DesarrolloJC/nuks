<?php
session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/home.class.php';

$DB = new DBConnection;
$homeSlider = new Home($DB);

$data = $homeSlider->getAll();
$nodo = array('data' => array());

$i = 0;

foreach ($data as $value) {

    $options = '<span>';
    $options .= '<i title="Editar Slider" class="fas fa-pen pre_edit-sm" onclick="editar(' . $value['slider_id'] . ');"></i>&nbsp;&nbsp;';
    $options .= '<i title="Eliminar Slider" class="fas fa-trash-alt pre_erase-sm pre_eraseFN" data-pro="' . $value['slider_id'] . '"></i>';
    $options .= '</span>';

    $img = '<img src="../../assets/images/sliderHome/' . $value['slider_img'] . '" alt="" style="width:250px;">';

    $nodo['data'][] = array(
        $value['slider_name'],
        $img,
        $value['slider_url'],
        $options
    );
}
echo json_encode($nodo);
