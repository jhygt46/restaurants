<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$core = new Core();

$dominios = $core->get_dominios_sin_dns();
$correos = $core->get_correos_no_ses();

echo "<pre>";
print_r($dominios);
echo "</pre>";

echo "<pre>";
print_r($correos);
echo "</pre>";

?>