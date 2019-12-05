<?php
session_start();

if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../errors/404.html');
    exit;
}
if((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off")) {
    $location = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=/admin">';
    exit;
}

if(!isset($_SESSION['user']['info']['id_user'])){
    include("login.php");
}else{
    include("inicio.php");
}

?>
