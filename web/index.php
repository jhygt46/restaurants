<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

if($_POST["tipo"] == 1){
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
    echo json_encode($core->get_web_js_data_remote());
}
if($_POST["tipo"] == 2){
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
    echo json_encode($core->enviar_pedido());
}
if($_POST["tipo"] == 3){
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
    echo json_encode($core->enviar_error());
}
if($_POST["tipo"] == 4){
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
    echo json_encode($core->ver_detalle());
}

?>