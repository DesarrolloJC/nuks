<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/seo.class.php';

$DB = new DBConnection;
$SEO = new Seo($DB);

$data = $SEO->getById($_GET['id']);
echo json_encode($data);

?>