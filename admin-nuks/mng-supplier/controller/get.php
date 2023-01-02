<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/supplier.class.php';

$DB = new DBConnection;
$suppliers = new Supplier($DB);

$data = $suppliers->getByCode($_GET['id']);
echo json_encode($data[0]);
