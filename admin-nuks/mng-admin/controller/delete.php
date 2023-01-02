<?php
session_start();
header('Content-Type: application/json');
require_once '../../php/connection.class.php';
require_once '../../php/table.class.php';
require_once '../model/users.class.php';

$DB = new DBConnection;
$users = new User($DB);

$id = $_GET['id'];
$users->delete($id);
