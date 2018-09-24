<?php
session_start();

header('Content-type: text/json');
header('Content-type: application/json');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}
echo "A1";
require_once($path."admin/class/guardar_class.php");
echo "A2";
$guardar = new Guardar();
$data = $guardar->process();
echo json_encode($data);


?>