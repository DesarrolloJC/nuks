<?php

class progress extends Table
{
    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_prog';

        $this->PRKEY = 'id_prog';
    }

    public function options()
    {
        $SQL = "SELECT * FROM {$this->TABLA} WHERE 1";
        $sql = sprintf($SQL);
        $data = $this->CONN->Query($sql);
        return $data;
    }

    public function saveProgress($array)
    {

        $campos = '';
        $datos = '';
        foreach ($array as $nombre => $valor) {
            $campos .= " $nombre,";
            $datos .= " '$valor',";
        }
        $this->getDuplicatedProducts($campos);
        $campos = preg_replace('/(,{1})$/', '', $campos);
        $datos = preg_replace('/(,{1})$/', '', $datos);
        $SQL = "INSERT INTO %s (%s) VALUES (%s)";

        $SQL = sprintf($SQL, $this->TABLA, $campos, $datos);
        $bool = $this->CONN->Execute($SQL);
        return $bool;

    }
}