<?php
session_start();

header('Content-type: text/json');
header('Content-type: application/json');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/admin/";
}else{
    $path = "/var/www/html/admin/";
}

require_once($path."class/guardar_class.php");
$guardar = new Guardar();
$data = $guardar->process();
echo json_encode($data);


?>