<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
redireccion_ssl();
esconder_index();
$url = url();

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url='.$url['path'].'admin">';
    exit;
}

if(!isset($_SESSION['user']['info']['id_user'])){
    include("login.php");
}else{
    include("inicio.php");
}

?>
