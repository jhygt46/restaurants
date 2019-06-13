<?php
session_start();

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

$info = $core->get_data($_SERVER["HTTP_HOST"]);
$core_class_iniciada = 1;
    
if(!isset($_SESSION['user']['info']['id_user'])){
    include("ingreso_login.php");
}else{
    include("inicio.php");
}

?>
