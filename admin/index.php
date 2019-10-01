<?php
session_start();

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}

if(!isset($_SESSION['user']['info']['id_usr'])){
    include("ingreso_login.php");
}else{
    include("inicio.php");
}

?>
