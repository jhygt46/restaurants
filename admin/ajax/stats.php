<?php

header('Content-type: text/json');
header('Content-type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();

echo json_encode($core->get_stats($_POST['tipo'], json_decode($_POST['locales']), $_POST['from'], $_POST['to']));

?>