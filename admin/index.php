<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
redireccion_ssl();
esconder_index();
$url = url();

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){

    setcookie("user_id", "", time() - 3600, "/");
    setcookie("user_code", "", time() - 3600, "/");
    setcookie("cookie_pos", "", time() - 3600, "/");
    setcookie("cookie_coc", "", time() - 3600, "/");
    die('<meta http-equiv="refresh" content="0; url='.$url['path'].'admin">');

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
