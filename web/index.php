<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
        
    esconder_index();
    redireccion_ssl();
    $url = url();


    if(isset($_POST["tipo"])){

        if($_POST["tipo"] == 1 || $_POST["tipo"] == 2 || $_POST["tipo"] == 3 || $_POST["tipo"] == 4 || $_POST["tipo"] == 5){
            require_once $url["dir"]."admin/class/core_class_prod.php";
            $core = new Core();
            if($_POST["tipo"] == 1){
                echo json_encode($core->get_web_js_data_remote());
            }
            if($_POST["tipo"] == 2){
                echo json_encode($core->enviar_pedido());
            }
            if($_POST["tipo"] == 3){
                echo json_encode($core->enviar_error());
            }
            if($_POST["tipo"] == 4){
                echo json_encode($core->ver_detalle());
            }
            if($_POST["tipo"] == 5){
                echo json_encode($core->enviar_contacto());
            }
        }else{
            header('HTTP/1.1 404 Not Found', true, 404);
            include($url["dir"].'errors/404.html');
            exit;
        }

    }else{

        header('HTTP/1.1 404 Not Found', true, 404);
        include(DIR.'errors/404.html');
        exit;

    }

?>