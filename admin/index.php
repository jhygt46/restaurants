<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
redireccion_ssl();
esconder_index();
$url = url();

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    $time = time() - 100;
    setcookie("user_id", "", $time);
    setcookie("user_code", "", $time);
    setcookie("cookie_pos", "", $time);
    setcookie("cookie_coc", "", $time);
    echo '<meta http-equiv="refresh" content="0; url='.$url['path'].'admin">';
    exit;
}

if(!isset($_COOKIE['user_id'])){
    include("login.php");
}else{
    if(isset($_COOKIE['user_code'])){
        include("inicio.php");
    }
    if(isset($_COOKIE['cookie_pos'])){
        echo '<meta http-equiv="refresh" content="0; url='.$url['path'].'admin/punto_de_venta">';
    }
    if(isset($_COOKIE['cookie_coc'])){
        echo '<meta http-equiv="refresh" content="0; url='.$url['path'].'admin/cocina">';
    }
}

?>
