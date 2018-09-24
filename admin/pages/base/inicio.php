<?php
session_start();

if($_SESSION['user']['info']['admin'] == 1){
    
    unset($_SESSION['user']['giro']);
    include("giros.php");
    
}
if($_SESSION['user']['info']['admin'] == 0){

    $_GET['id_gir'] = $_SESSION['user']['giro']['id_gir'];
    $_GET['nombre'] = $_SESSION['user']['giro']['nombre'];
    include("ver_giro.php");

}

