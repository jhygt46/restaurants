<?php

    if($_POST["accion"] == "despacho_domicilio"){
        require_once "../admin/class/restaurant_class.php";
        $core = new Core();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
        echo json_encode($info);
    }

?>


