<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/login_class.php";

header('Content-type: text/json');
header('Content-type: application/json');

$login = new Login();
if($_POST['accion'] == "login"){
    $data = $login->login_back();
}
if($_POST['accion'] == "recuperar_password"){
    $data = $login->recuperar_password();
}
if($_POST['accion'] == "nueva_password"){
    $data = $login->nueva_password();
}

echo json_encode($data);


?>