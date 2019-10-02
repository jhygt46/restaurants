<?php
session_start();

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

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
