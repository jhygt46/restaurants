<?php
session_start();

if($_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}

if($_SERVER['HTTP_HOST'] == "localhost"){
    require('class/core_class.php');
    $core = new Core();
    $info = $core->get_data('www.fireapp.cl');
}else{
    require('/var/www/html/restaurants/admin/class/core_class.php');
    $core = new Core();
    $info = $core->get_data();
}

if($info['id_gir'] == 0 && $_SERVER['HTTP_HOST'] != "misitiodelivery.cl"){
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://misitiodelivery.cl/admin');
}
if($info['id_gir'] > 0){
    if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") && $info['ssl'] == 1){
        $location = 'https://'.$_SERVER['HTTP_HOST']."/admin";
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $location);
    }
}
    
if(!isset($_SESSION['user']['info']['id_user'])){
    include("ingreso_login.php");
}else{
    include("inicio.php");
}

?>
