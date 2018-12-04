<?php

require('admin/class/core_class.php');
$core = new Core();

$info = $core->get_data('www.mikasushi.cl');
$code = $core->socket_code($_GET['id_loc'], $info['id_gir']);

if($code === null){
    echo "ERROR: SIN ACCESO AL SISTEMA";
    exit;
}

$pedidos = $core->get_ultimos_pedidos($_GET['id_loc']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info["font"]['family']; ?>" rel="stylesheet">
        
        <link rel="stylesheet" href="<?php echo $info["css_reset"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_pos"]; ?>" media="all" />
        
        <script src="http://35.196.220.197/socket.io/socket.io.js"></script>
        
        <script src="<?php echo $info["js_jquery"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_pos"]; ?>" type="text/javascript"></script>
        <script> var local_code = '<?php echo $code; ?>'; </script>
        <script> var pedidos = <?php echo json_encode($pedidos); ?>; </script>
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
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title">Ingresar Nuevo Pedido</div>
                        <div class="n_info clearfix">
                            <div class="n_info1">
                                <div class="n_info1_a clearfix">
                                    <div class="n_info1_a0">Retiro en Local</div>
                                    <div class="n_info1_a0">Despacho a Domicilio</div>
                                </div>
                                <div class="n_info1_b clearfix">
                                    <div class="n_info1_b0">Direccion</div>
                                    <div class="n_info1_b1"><input type="text" id="ped_direccion" style="width: 100%" ></div>
                                </div>
                                <div class="n_info1_b clearfix">
                                    <div class="n_info1_b0">Telefono</div>
                                    <div class="n_info1_b1"><input type="text" id="ped_telefono" style="width: 100%" ></div>
                                </div>
                            </div>
                            <div class="n_info2">2</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cont_sis clearfix">
                <div class="pedidos">
                    <div class="titulo">
                        <div class="ttl">Mis Pedidos</div>
                        <div class="opciones">
                            <div class="nuevo" onclick="ver_pedido(-1)">+</div>
                            <!--<div class="config">+</div>-->
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
                        <div class="opciones">
                            <div class="nuevo" onclick="categoria_padre()"><</div>
                            <!--<div class="config">+</div>-->
                        </div>
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