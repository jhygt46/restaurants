<?php

    if($_POST["accion"] == "despacho_domicilio"){
        require_once "../admin/class/restaurant_class.php";
        $core = new Rest();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "get_users_pedido"){
        require_once "../admin/class/restaurant_class.php";
        $core = new Rest();
        $info = $core->get_users_pedido($_POST["telefono"]);
        echo json_encode($info);
    }

?>


