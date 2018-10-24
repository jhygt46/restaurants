<?php

require('admin/class/core_class.php');
$core = new Core();

if(isset($_GET['param_dom'])){
    $info = $core->get_data($_GET['param_dom']);
}else{
    //$info = $core->get_data();
    $info = $core->get_data('www.runasushi.cl');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info["font"]['family']; ?>" rel="stylesheet">
        <link rel="stylesheet" href="css/reset.css" media="all" />
        <link rel="stylesheet" href="css/pos.css" media="all" />
        <script src="http://35.196.220.197/socket.io/socket.io.js"></script>
        <script src="<?php echo $info["js_jquery"]; ?>" type="text/javascript"></script>
        <script src="http://35.185.64.95/js/data/7f3e85e0c8cfb8a85c02.js" type="text/javascript"></script>
        <script src="js/pos.js" type="text/javascript"></script>
        <script> var local_code = "anb7sd-12s9ksm"; </script>
        <style>
            body{
                font-family: <?php echo $info["font"]['css']; ?>;
            }
        </style>
    </head>
    <body onload="socket_init()">
        <div class="contenedor">
            <div class="pop_up" style="display: none">
                <div class="cont_pop_up">
                    <div class="nuevo_pedido vhalign">
                        <div class="n_title">Ingresar Nuevo Pedido</div>
                    </div>
                    <div class="modificar_pedido vhalign">
                        <div class="n_title">Modificar Pedido #347</div>
                    </div>
                </div>
            </div>
            <div class="cont_sis clearfix">
                <div class="pedidos">
                    <div class="titulo">
                        <div class="ttl">Mis Pedidos</div>
                        <div class="opciones">
                            <div class="nuevo">+</div>
                            <div class="config">+</div>
                        </div>
                    </div>
                    <div class="contenido">
                        <div class="lista_pedidos">
                            
                        </div>
                    </div>
                </div>
                <div class="categorias">
                    <div class="titulo">
                        <div class="ttl">Categorias</div>
                    </div>
                    <div class="contenido">
                        <div class="lista_categorias"></div>
                    </div>
                </div>
                <div class="categorias">
                    <div class="titulo">
                        <div class="ttl">Productos</div>
                    </div>
                    <div class="contenido">
                        <div class="lista_productos"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>