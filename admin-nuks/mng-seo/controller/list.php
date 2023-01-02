<?php
session_start();
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/seo.class.php';

$DB = new DBConnection;
$SEO = new Seo($DB);

$data = $SEO->getAll();
$nodo = array('data' => array());

foreach ($data as $value) {

    $options = '<span>';
    $options .= '<i title="Editar SEO" class="fas fa-pen pre_edit-sm" onclick="editar(' . $value['seo_id'] . ');"></i>&nbsp;&nbsp;';
    $options .= '</span>';


    $nodo['data'][] = array(
        $value['seo_keywords'],
        $value['seo_description'],
        $options
    );
}
echo json_encode($nodo);
?>