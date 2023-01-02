<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/home.class.php';

$DB = new DBConnection;
$homeSlider = new Home($DB);

$currSlide = $homeSlider->getById($_POST["id"]);

$DIRIMG = '../../../assets/images/sliderHome/';
$_valores = array();
$hecho = array('success' => true, 'title' => 'Exito!', 'msg' => 'Información actualizada correctamente.', 'class' => 'success', 'final' => 'tabla.ajax.reload();$("#EdUs").modal("hide");');
$error = array('success' => false, 'title' => 'Error!', 'msg' => 'Ocurrió un error, inténtelo más tarde.', 'class' => 'error');

//Si se cambia el titulo se actualiza, sino, se queda el que estaba
if ($_POST["name"] != $currSlide['slider_name']) {
    $_valores['slider_name'] = $homeSlider->SanitizarTexto($_POST['name']);
} else {
    $_valores['slider_name'] = $currSlide['slider_name'];
}
//Si se cambia la url se actualiza, sino, se queda la que estaba
if ($currSlide['slider_url'] != $_POST['url']) {
    $_valores['slider_url'] = $_POST['url'];
} else {
    $_valores['slider_url'] = $currSlide['slider_url'];
}


if ($_FILES['imagen']['name'] && "webp/" . $_FILES['imagen']['name'] != $currSlide['slider_img']) {
    $IMG = "art-promo-" . $_valores['slider_name'] . "-" . $homeSlider->makeURLFILE($_FILES["imagen"]['name']);
    $fileNameNoext = explode(".", $IMG)[0];
    $fileExtension = explode(".", $IMG)[1];
    $newFileName = "webp/" . $fileNameNoext . ".webp";

    if ($fileExtension == "jpg") {
        move_uploaded_file($_FILES["imagen"]['tmp_name'], $DIRIMG . $IMG);
        $img = imagecreatefromjpeg($DIRIMG . $IMG);
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        imagewebp($img, $DIRIMG . "/" . $newFileName, 80);
        imagedestroy($img);
    } else if ($fileExtension == "png") {
        move_uploaded_file($_FILES["imagen"]['tmp_name'], $DIRIMG . $IMG);
        $img = imagecreatefrompng($DIRIMG . $IMG);
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        imagewebp($img, $DIRIMG . "/" . $newFileName, 80);
        imagedestroy($img);
    } else if ($fileExtension == "webp") {
        move_uploaded_file($_FILES["imagen"]['tmp_name'], $DIRIMG . "webp/" . $IMG);
    }

    //Delete old files, both webp and og extension
    if (unlink(realpath($DIRIMG . $currSlide["slider_img"]))) {
        $files = scandir($DIRIMG);
        foreach ($files as $file) {
            if (strpos(explode(".", explode("/", $currSlide["slider_img"])[1])[0], explode(".", $file)[0]) !== false) {
                unlink(realpath($DIRIMG . $file));
            }
        }
    } else {
        var_dump("ocurrio un error");
    }


    $_valores['slider_img'] = $newFileName;

} else {
    $_valores["slider_img"] = $currSlide["slider_img"];
}

if ($homeSlider->update($_valores, $_POST['id'])) echo json_encode($hecho);
else echo json_encode($error);
