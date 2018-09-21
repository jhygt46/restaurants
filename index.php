<?php
    session_start();
    
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
