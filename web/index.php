<?php

if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../errors/404.html');
    exit;
}

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

if(isset($_POST["tipo"])){

    if($_POST["tipo"] == 1 || $_POST["tipo"] == 2 || $_POST["tipo"] == 3 || $_POST["tipo"] == 4){
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
            echo $core->ver_detalle();
        }
        if($_POST["tipo"] == 5){
            echo json_encode($core->enviar_contacto());
        }
    }else{
        header('HTTP/1.1 404 Not Found', true, 404);
        include(DIR.'errors/404.html');
        exit;
    }

}else{

    header('HTTP/1.1 404 Not Found', true, 404);
    include(DIR.'errors/404.html');
    exit;

}

?>