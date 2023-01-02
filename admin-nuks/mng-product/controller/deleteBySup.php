<?php

//TODO convert this to MVC
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../../mng-category/model/category.class.php';
require_once '../../mng-product/model/porduct.class.php';

$DB = new DBConnection;

$CATES = new Category($DB);
$PROD = new Product($DB);
$SUPPLIERS = new Supplier($DB);


function removeByProv($conn, $sup)
{

    $SQL = "SELECT * FROM tbl_product WHERE supplier = $sup";
    $res = $conn->Query($SQL);

    while ($res) {
        foreach ($res as $row) {
            var_dump($row);
            $prod_webp = "../../../img/product/" . $row["img"];
            $prod = "../../../img/product/" . explode(".", explode("/", $row["img"])[1])[0] . ".jpg";
            $id = $row["product_id"];

            $sql2 = "DELETE FROM tbl_product WHERE product_id = $id";
            $res2 = $conn->Execute($sql2);

            if ($res2) {
                if ($prod) {
                    unlink($prod);
                }
                if ($prod_webp) {
                    unlink($prod_webp);
                }

                $sqlSlides = "SELECT * FROM tbl_slider_product WHERE prod_id = $id";
                $resSlides = $conn->Query($sqlSlides);
                foreach ($resSlides as $Qslide) {
                    $slide = $Qslide[3];
                    $slid = $Qslide[0];

                    $deleteSlide = "DELETE FROM tbl_slider_product WHERE slider_prod_id = $slid";
                    $res = $conn->Execute($deleteSlide);

                    if ($slide) {
                        unlink("../../../img/product/" . $slide);
                    }
                }
            } else {
                echo "Ocurrio un error";
                die();
            }
        }
    }

}

if ($_REQUEST["id"] && $_REQUEST["id"] != "all") {
    removeByProv($DB, strval($_REQUEST["id"]));
} else if ($_REQUEST["id"] == "all") {
    $suppliers = $SUPPLIERS->getAll();

    foreach ($suppliers as $supplier) {
        removeByProv($DB, $supplier["supplier_code"]);
    }
} else {
    echo "Que hay de nuevo viejo";
}
