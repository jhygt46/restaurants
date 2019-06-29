<?php
require_once "/var/www/html/restaurants/admin/class/core_class_prod.php";

header('Content-type: text/json');
header('Content-type: application/json');

$core = new Core();
echo json_encode($core->get_ultimos_pedidos($_POST["id_ped"]));

?>