<?php

require_once "/var/www/html/restaurants/admin/class/core_class_prod.php";
$core = new Core();

if($_POST["tipo"] == 1){
    echo json_encode($core->get_web_js_data_remote());
}
if($_POST["tipo"] == 2){
    echo json_encode($core->enviar_pedido());
}
if($_POST["tipo"] == 3){
    $core->enviar_error();
}
if($_POST["tipo"] == 4){
    echo json_encode($core->ver_detalle());
}

?>