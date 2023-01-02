<?php

class Supplier extends Table
{

    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_supplier';

        $this->PRKEY = 'supplier_id';
    }

    public function getByName($name)
    {
        $SQL = "SELECT {$this->PRKEY} FROM {$this->TABLA} WHERE supplier_name = '{$name}'";
        $data = $this->CONN->Query($SQL);
        $id = $data[0]['supplier_id'];
        return $id;
    }

    public function getByCode($code)
    {
        $SQL = "SELECT * FROM {$this->TABLA} WHERE supplier_code = '{$code}'";
        $data = $this->CONN->Query($SQL);
        return $data;
    }

    public function getApi($ID)
    {
        $SQL = "SELECT supplier_api FROM {$this->TABLA} WHERE supplier_id = '{$ID}'";
        $data = $this->CONN->Query($SQL);
        $api = $data[0]['supplier_api'];
        return $api;
    }

    public function getAll()
    {
        $SQL = "SELECT * FROM {$this->TABLA} WHERE 1";
        $data = $this->CONN->Query($SQL);
        return $data;
    }

}
