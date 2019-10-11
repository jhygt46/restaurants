<?php

$file = explode("/", $_SERVER["REQUEST_URI"]);
if($file[count($file) - 1] != ""){
    header('HTTP/1.0 404 Not Found');
    exit;
}

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();

if($_POST["tipo"] == 1){
    echo json_encode($core->get_web_js_data_remote());
}
if($_POST["tipo"] == 2){
    echo json_encode($core->enviar_pedido());
}
if($_POST["tipo"] == 3){
    echo json_encode($core->enviar_error());
}
if($_POST["tipo"] == 4){
    echo json_encode($core->ver_detalle());
}

?>