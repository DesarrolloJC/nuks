<?php
session_start();
header('Content-Type: application/json');

require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/porduct.class.php';
require_once '../model/sliderProduct.class.php';
require_once '../../../function/services.class.php';

$DB = new DBConnection;
$PRODUCTOS = new Product($DB);
$SLIDER = new Slider($DB);
$SERVICES = new services();

$DB->BeginTransaction();

$LISTO = array('success' => true, 'title' => 'Exito!', 'msg' => 'Actualizado correctamente.', 'class' => 'success', 'final' => 'location.href="./"');
$ERRGN = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');
$ERRFI = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error al subir archivo: ', 'class' => 'error');

$DIRIMG = '../../../img/product/';
$_UPD = array();

$URL = $PRODUCTOS->makeURL($_POST['nombre']);
$ID = $_POST['id'];
// echo "El id es: 25";
var_dump($_POST);
die();

$_UPD['name'] = $PRODUCTOS->SanitizarTexto($_POST['nombre']);
$_UPD['description'] = $_POST['description'];
$_UPD['info'] = $_POST['contenido'];
$_UPD['color'] = $_POST['colors'];
$_UPD['category'] = array_pop($_POST['categoria']);
$_UPD['price'] = $_POST['price'];
$_UPD['price_general'] = ($_UPD['price']) * (1.35);
$_UPD['price_client'] = ($_UPD['price']) * (1.30);
$_UPD['price_distributor_level_one'] = ($_UPD['price']) * (1.25);
$_UPD['price_distributor_level_two'] = ($_UPD['price']) * (1.23);
$_UPD['price_distributor_level_three'] = ($_UPD['price']) * (1.21);
$_UPD['url'] = $URL;
$_UPD["prov_website"] = $_POST["prov_url"];

$imgExist = $SLIDER->getImgProduct($ID);

// var_dump($imgExist);

if ($_FILES['img']['name'] == '') {
    $IMG = $imgExist["img"];
    $_UPD['img'] = $IMG;
} elseif (!$_FILES['img']['name'] == '') {
    $IMG = $_FILES['img']['name'];
    $SERVICES->moveImg($_FILES['img']['tmp_name'], $DIRIMG, $IMG, $_FILES['img']['error']);
//    move_uploaded_file($_FILES['img']['tmp_name'], $DIRIMG . $IMG);
    $_UPD['img'] = $IMG;
} else if ($_FILES['img']['error'] != 4) {
    $DB->Rollback();
    $ERRFI['msg'] = $PRODUCTOS->getFileErrorMSG($_FILES['img']['error']);
    echo json_encode($ERRFI);
    exit;
}

// die();

// //Guardar imagen principal
// if ($PRODUCTOS->getFileErrorMSG($_FILES['img']['error']) == 0 ) {
//   // $IMG = $_FILES['img']['name'];
//   // move_uploaded_file($_FILES['img']['tmp_name'],$DIRIMG.$IMG);
//   // $_UPD['img'] = $IMG;
// } else if ( $_FILES['img']['error'] != 4 ) {
//   $DB->Rollback();
//   $ERRFI['msg'] = $PRODUCTOS->getFileErrorMSG($_FILES['img']['error']);
//   echo json_encode( $ERRFI );
//   exit;
// }

foreach ($_FILES["slider"]['error'] as $i => $e) {
    $_SLI = array();
    if ($e == 0) {
        $URLFILE = $_FILES['slider']['name'][$i];
        $IMG = $URLFILE;
        move_uploaded_file($_FILES['slider']['tmp_name'][$i], $DIRIMG . $IMG);
        $_SLI['slider_img'] = $IMG;
        $_SLI['prod_id'] = $ID;
        $SLIDER->insert($_SLI);
    } else if ($e != 4) {
        $DB->Rollback();
        $ERRFI['msg'] = $SLIDER->getFileErrorMSG($_FILES['slider']['error'][$i]);
        echo json_encode($ERRFI);
        exit;
    }
}


if ($PRODUCTOS->update($_UPD, $ID)) {
    $DB->Commit();
    echo json_encode($LISTO);
    exit;
} else {
    $DB->Rollback();
    echo json_encode($ERRGN);
    exit;
}

// if ( !$PRODUCTOS->update( $_UPD, $ID ) ) {
//   $DB->Rollback();
//   echo json_encode( $ERRGN );
//   exit;
// }else{

// }

// $DB->Commit();
// echo json_encode( $LISTO );
// exit;
