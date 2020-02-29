<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
esconder_index();
$url = url();


require_once $url["dir"]."admin/class/guardar_class.php";

header('Content-type: text/json');
header('Content-type: application/json');

$core = new Guardar();
$core->process();

?>