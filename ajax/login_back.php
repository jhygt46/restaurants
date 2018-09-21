<?php
session_start();

header('Content-type: text/json');
header('Content-type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'];
if($_SERVER['HTTP_HOST'] == "localhost"){
    $path .= "/";
    $path_class = $path."easyapps/class/";
    $path_n = $path."easyapps/";
    
}else{
    //$path_class = $path."admin/class/";
    //$path_n = $path."admin/";
}

require_once($path_class."login_class.php");
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