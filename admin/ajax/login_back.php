<?php
session_start();

header('Content-type: text/json');
header('Content-type: application/json');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/login_class.php");
$login = new Login();

if($_POST['accion'] == "login"){
    $data = $login->login_back();
}
if($_POST['accion'] == "recuperar"){
    $data = $login->enviar_clave();
}
if($_POST['accion'] == "resetpass"){
    $data = $login->crear_password();
}

echo json_encode($data);


?>