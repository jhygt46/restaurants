<?php

    if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
        header('HTTP/1.1 404 Not Found', true, 404);
        include('../errors/404.html');
        exit;
    }

    header('Content-type: text/json');
    header('Content-type: application/json');

    if($_SERVER["HTTP_HOST"] == "localhost"){
        define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
        define("DIR", DIR_BASE."restaurants/");
    }else{
        define("DIR_BASE", "/var/www/html/");
        define("DIR", DIR_BASE."restaurants/");
    }

    if($_POST["accion"] == "despacho_domicilio"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "get_users_pedido"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->get_pos_direcciones($_POST["telefono"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "get_despacho"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->get_info_despacho($_POST["lat"], $_POST["lng"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "get_pos_pedidos"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->get_ultimos_pedidos($_POST["id_ped"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "del_pos_pedido"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->del_pos_direcciones($_POST["id_pdir"]);
        echo json_encode($info);
    }
    if($_POST["accion"] == "set_web_pedido"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->set_web_pedido();
        echo json_encode($info);
    }
    if($_POST["accion"] == "crear_dominio"){
        require_once DIR."admin/class/guardar_class.php";
        $core = new Core();
        $info = $core->crear_dominio();
        echo json_encode($info);
    }
    if($_POST["accion"] == "enviar_contacto_msd"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->enviar_contacto_msd();
        echo json_encode($info);
    }
    if($_POST["accion"] == "cambiar_estado"){
        require_once DIR."admin/class/core_class_prod.php";
        $core = new Core();
        $info = $core->cambiar_estado();
        echo json_encode($info);
    }

?>


