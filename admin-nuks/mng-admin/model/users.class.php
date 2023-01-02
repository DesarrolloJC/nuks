<?php

class User extends Table
{

    public function __construct($DB)
    {
        parent::__construct($DB);
        $this->TABLA = 'tbl_user';

        $this->PRKEY = 'user_id';
    }

    public function login($user, $password)
    {
        $sql = "SELECT * FROM {$this->TABLA} WHERE user_email = \"%s\" AND user_pass = \"%s\"";
        $sql = sprintf($sql, $user, $password);
        $data = $this->CONN->Query($sql);
        return $data;
    }

}