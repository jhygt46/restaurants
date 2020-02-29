<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
esconder_index();
$url = url();

require_once $url["dir"]."admin/class/login_class.php";

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