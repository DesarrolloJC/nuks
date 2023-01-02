<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/home.class.php';

$DB = new DBConnection;
$homeSlider = new Home($DB);

$data = $homeSlider->getById($_GET['id']);
echo json_encode($data);

?>