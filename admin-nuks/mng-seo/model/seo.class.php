<?php

class Seo extends Table{
    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_seo';
        $this->PRKEY = 'seo_id';
    }
}

?>