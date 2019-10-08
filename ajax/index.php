<?php

    if($_SERVER["HTTP_HOST"] == "localhost"){
        define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
        define("DIR", DIR_BASE."restaurants/");
    }else{
        define("DIR_BASE", "/var/www/html/");
        define("DIR", DIR_BASE."restaurants/");
    }
    if($_POST["accion"] == "despacho_domicilio"){
        require_once DIR."admin/class/restaurant_class.php";
        $core = new Rest();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "get_users_pedido"){
        require_once DIR."admin/class/restaurant_class.php";
        $core = new Rest();
        $info = $core->get_users_pedido($_POST["telefono"]);
        echo json_encode($info);
    }

?>


