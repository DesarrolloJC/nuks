<?php

class Rol extends Table
{

    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_roles';

        $this->PRKEY = 'id_role';
    }

    public function options(){
        $SQL = "SELECT * FROM {$this->TABLA} WHERE 1";
        $sql = sprintf($SQL);
        $data = $this->CONN->Query($sql);
        return $data;
    }

}
