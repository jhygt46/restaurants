<?php
session_start();

if($_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

$info = $core->get_data($_SERVER["HTTP_HOST"]);
$core_class_iniciada = 1;

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off"){
    if($info['ssl'] == 0 && $_SERVER["HTTP_HOST"] != "localhost"){
        $location = 'https://misitiodelivery.cl/admin';
        header('HTTP/1.1 302 Moved Temporarily');
        header('Location: ' . $location);
    }
    if($info['ssl'] == 1){
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
