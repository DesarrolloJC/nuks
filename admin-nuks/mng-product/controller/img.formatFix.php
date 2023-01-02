<?php
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../../mng-category/model/category.class.php';
require_once '../../mng-product/model/porduct.class.php';

$DB = new DBConnection;

$CATES = new Category($DB);
$PROD = new Product($DB);

$dir = '../../../img/product/';
$files = scandir($dir);

for ($i = 0; $i < count($files); $i++) {
    if (explode(".", $files[$i])[1] == 'jpg') {
        $newFileName = explode(".", $files[$i])[0] . ".webp";
        $img = imagecreatefromjpeg($dir . $files[$i]);
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        imagewebp($img, $dir . "/webp/" . $newFileName, 80);
        imagedestroy($img);
        $SQL = "UPDATE tbl_product SET img = 'webp/$newFileName' WHERE img = '$files[$i]'";
        $RES = $DB->Execute($SQL);
        $SQL2 = "UPDATE tbl_slider_product SET slider_img = 'webp/$newFileName' WHERE slider_img = '$files[$i]'";
        $RES2 = $DB->Execute($SQL2);
    } else if (explode(".", $files[$i])[1] == 'png') {
        echo $dir . $files[$i];
    }
}
