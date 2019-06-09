<?php
require_once "/var/www/html/restaurants/admin/class/stats_class.php";

header('Content-type: text/json');
header('Content-type: application/json');

$stats = new Stats();
$data = $stats->process();
echo json_encode($data);

?>