<?php
require_once "/var/www/html/restaurants/admin/class/guardar_class.php";

header('Content-type: text/json');
header('Content-type: application/json');

$core = new Core();
echo json_encode($core->set_web_pedido());

?>