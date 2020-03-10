<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
redireccion_ssl();
esconder_index();
$url = url();

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    setcookie("user_id");
    setcookie("user_code");
    setcookie("cookie_pos");
    setcookie("cookie_coc");
    echo '<meta http-equiv="refresh" content="0; url='.$url['path'].'admin">';
    exit;
}

if(!isset($_COOKIE['user']['info']['id_user'])){
    include("login.php");
}else{
    include("inicio.php");
}

?>
