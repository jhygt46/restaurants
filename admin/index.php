<?php

session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    require('class/core_class.php');
}else{
    require('/var/www/html/restaurants/admin/class/core_class.php');
}

$core = new Core();
$info = $core->get_data();

echo "<pre>";
print_r($info);
echo "</pre>";

/*
if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") && $_SERVER['HTTP_HOST'] != "www.misitiodelivery.cl" && $_SERVER['HTTP_HOST'] != "localhost") {
    $location = 'http://www.misitiodelivery.cl/admin';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

if($_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}
    
if(!isset($_SESSION['user']['info']['id_user'])){
    include("ingreso_login.php");
}else{
    include("inicio.php");
}
*/
?>
