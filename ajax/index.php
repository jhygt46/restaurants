<?php
session_start();

header('Content-type: text/json');
header('Content-type: application/json');

$path = $_SERVER['DOCUMENT_ROOT'];
if($_SERVER['HTTP_HOST'] == "localhost"){
    $path .= "/";
    $path_class = $path."/easyapps/class/";
    $path_n = $path."/easyapps/";
    
}else{
    //$path_class = $path."admin/class/";
    //$path_n = $path."admin/";
}

require_once($path_class."guardar_class.php");
$guardar = new Guardar();
$data = $guardar->process();
echo json_encode($data);


?>