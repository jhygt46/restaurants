<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");

if($_SESSION['user']['info']['admin'] == 1){
    
    include($path."admin/pages/base/giros.php");
    
}
if($_SESSION['user']['info']['admin'] == 0){

    $fireapp = new Core();
    $giros_user = $fireapp->get_giros_user();
    if(count($giros_user) == 1){
        $_GET["id_gir"] = $giros_user[0]['id_gir'];
        include($path."admin/pages/base/ver_giro.php");
    }else{
        include($path."admin/pages/base/giros.php");
    }

}

