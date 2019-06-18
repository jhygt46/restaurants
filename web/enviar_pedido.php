<?php

require_once "/var/www/html/restaurants/admin/class/core_class_prod.php";
$core = new Core();
echo json_encode($core->enviar_pedido());

?>