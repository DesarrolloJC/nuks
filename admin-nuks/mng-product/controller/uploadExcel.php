<?php

$name = $_FILES['file']['name'];
$type = $_FILES['file']['type'];
$tmp_name = $_FILES['file']["tmp_name"];

$uploaddir = '../../../docs/temp/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    echo $uploadfile;
} else {
    echo "No se pudo subir";
}
