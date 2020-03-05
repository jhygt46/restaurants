<?php

header('Content-type: text/json');
header('Content-type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."db.php";
require_once $url["dir_base"]."config/config.php";

$con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
print_table($con, $_GET["aux"]);

?>