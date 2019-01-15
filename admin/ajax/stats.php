<?php
session_start();

header('Content-type: text/json');
header('Content-type: application/json');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/admin/";
}else{
    $path = "/var/www/html/restaurants/admin/";
}

require_once($path."class/stats_class.php");
$stats = new Stats();
$data = $stats->process();
echo json_encode($data);

?>