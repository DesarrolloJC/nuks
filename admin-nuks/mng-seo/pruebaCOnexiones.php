<?php

function conPromo()
{
    $servername = "promosoluciones.com.mx";
    $username = "promoso_symfony";
    $password = "pitoconcaca";
    $database = "promoso_presta13";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function conCrm()
{
    $servername = "localhost";
    $username = "username";
    $password = "password";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

?>
