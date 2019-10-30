<?php
session_start();

if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../errors/404.html');
    exit;
}

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=https://misitiodelivery.cl/admin">';
    exit;
}

if(!isset($_SESSION['user']['info']['id_user'])){
    include("ingreso_login.php");
}else{
    include("inicio.php");
}

?>
