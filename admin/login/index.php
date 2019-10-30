<?php

if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../../errors/404.html');
    exit;
}

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

if($_POST['accion'] == "login"){
    $login = new Login();
    $data = $login->login_back();
    echo json_encode($data);
}
if($_POST['accion'] == "recuperar_password"){
    $login = new Login();
    $data = $login->recuperar_password();
    echo json_encode($data);
}
if($_POST['accion'] == "nueva_password"){
    $login = new Login();
    $data = $login->nueva_password();
    echo json_encode($data);
}




?>