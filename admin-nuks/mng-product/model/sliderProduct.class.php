<?php

require_once '../../php/connection.class.php';

class Slider extends Table
{

    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_slider_product';
        $this->PRKEY = 'slider_prod_id';
    }

    public function getImgProduct($ID)
    {
        $SQL = "SELECT img FROM tbl_product WHERE product_id = $ID";
        // $SQL = sprintf($SQLU, $ID);
        $data = $this->CONN->Query($SQL);
        // return $data[0];
        return $data[0];
    }
}
