<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/restaurant_class.php");
$rest = new Rest();
echo json_encode($rest->get_info());

?>


