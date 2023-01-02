<?php

class Home extends Table
{
    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_slider';
        $this->PRKEY = 'slider_id';
    }
}
