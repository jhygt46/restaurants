<?php
    session_start();
    
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
        $location = 'https://www.misitiodelivery.cl/admin';
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
        include("login.php");
    }else{
        include("inicio.php");
    }

?>
