<?php
session_start();
//$res = post_captcha($_POST['g-recaptcha-response']);
require_once 'connection.class.php';
require_once 'table.class.php';
require_once '../mng-admin/model/users.class.php';

$DB = new DBConnection;
$users = new User($DB);

$user = $_POST['email'];
$pass = md5($_POST['pass']);
$login = $users->login($user, $pass);


if (count($login) == 0) {
    $_return['success'] = false;
    $_return['msg'] = 'La informacion es incorrecta.';
    echo json_encode($_return);
    die();
} else {
    $_SESSION['login'] = true;
    $_SESSION['id'] = $login[0]['user_id'];
    $_SESSION['user'] = $login[0]['user_name'];
    $_SESSION['userLastName'] = $login[0]['user_lastname'];
    $_SESSION['email'] = $login[0]['user_email'];
    $_SESSION['role'] = $login[0]['user_role'];

    $_return['success'] = true;
    $_return['msg'] = 'Bienvenido';
    $_return['role'] = $login[0]['user_role'];
    echo json_encode($_return);
    die();
}
