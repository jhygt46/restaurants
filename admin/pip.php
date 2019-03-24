<?php

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = "C:/AppServ/www/restaurants";
}else{
    $path = "/var/www/html/restaurants";
}

require($path."/admin/class/core_class.php");
$core = new Core();

echo "<pre>";
print_r($core->get_info_despacho($_GET["lat"], $_GET["lng"]));
echo "</pre>";

?>