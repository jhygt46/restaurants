<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/guardar_class.php";

header('Content-type: text/json');
header('Content-type: application/json');

$guardar = new Guardar();
$data = $guardar->process();
echo json_encode($data);

?>