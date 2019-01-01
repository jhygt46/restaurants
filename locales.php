<?php
date_default_timezone_set('America/Santiago');

require('admin/class/core_class.php');
$core = new Core();

$info = $core->get_data('www.izusushi.cl');
$code = $core->socket_code($_GET['id_loc'], $info['id_gir']);

if($code === null){
    echo "ERROR: SIN ACCESO AL SISTEMA";
    exit;
}

$pedidos = $core->get_ultimos_pedidos($_GET['id_loc'], null);

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
        <script src="<?php echo $info["js_pos_lista"]; ?>" type="text/javascript"></script>
        <script> var local_code = '<?php echo $code; ?>'; </script>
        <script> var pedidos = <?php echo json_encode($pedidos); ?>; </script>
        <style>
            body{
                font-family: <?php echo $info["font"]['css']; ?>;
            }
        </style>
    </head>
    <body>
        <div class="contenedor">
            <div class="pop_up" style="display: none">
                <div class="cont_pop_up">
                    <div class="nuevo_pedido vhalign">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_info">
                            <div class="data_info">
                                <div class="nom_tel clearfix">
                                    <div class="nom">
                                        <span>Nombre: </span>
                                        <input id="nombre" type="text" />
                                    </div>
                                    <div class="tel">
                                        <span>Telefono: </span>
                                        <input id="telefono" type="tel" />
                                    </div>
                                </div>
                                <div class="nom_tel clearfix">
                                    <div class="tipo">
                                        <span>Tipo: </span>
                                        <select id="despacho" onchange="change_despacho(this)"><option value="1">Despacho Domicilio</option><option value="0">Retiro Local</option></select>
                                    </div>
                                </div>
                                <div class="nom_tel t_despacho clearfix">
                                    <div class="direccion">
                                        <span>Direccion: </span>
                                        <input id="direccion" type="text" />
                                    </div>
                                    <div class="depto">
                                        <span>Depto: </span>
                                        <input id="depto" type="text" />
                                    </div>
                                </div>
                                <div class="preguntas">
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Wasabi</div>
                                        <div class="pre_check"><input type="checkbox" id="pre_wasabi" /></div>
                                    </div>
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Gengibre</div>
                                        <div class="pre_check"><input type="checkbox" id="pre_gengibre" /></div>
                                    </div>
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Embarazadas</div>
                                        <div class="pre_check"><input type="checkbox" id="pre_embarazadas" /></div>
                                    </div>
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Soya</div>
                                        <div class="pre_check"><input type="checkbox" id="pre_soya" /></div>
                                    </div>
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Teriyaki</div>
                                        <div class="pre_check"><input type="checkbox" id="pre_teriyaki" /></div>
                                    </div>
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Palitos</div>
                                        <div class="pre_check"><select id="pre_palitos"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div>
                                    </div>
                                    
                                </div>
                                <div class="pregunta clearfix" style="padding-top: 20px">
                                        <div class="pre_nom">Repartidor</div>
                                        <div class="pre_check"><select id="id_mot"><option value="0">Ninguno</option><?php for($i=0; $i<count($info['motos']); $i++){ ?><option value="<?php echo $info['motos'][$i]['id_mot']; ?>" <?php echo ($i == 0) ? "selected" : "" ; ?>><?php echo $info['motos'][$i]['nombre']; ?></option><?php } ?></select></div>
                                    </div>
                            </div>
                        </div>
                        <div class="n_submit">
                            <input type="button" value="Enviar" onclick="done_pedido()" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="cont_sis clearfix">
                <div class="alert_socket">
                    <div class="text">
                        <div class="text_1">ATENCION:</div>
                        <div class="text_2">Al sistema no estan llegando los pedidos automaticamente</div>
                    </div>
                </div>
                <div class="pedidos">
                    <div class="titulo">
                        <div class="ttl">Mis Pedidos</div>
                        <div class="opciones">
                            <div class="nuevo" onclick="ver_pedido(-1, null)">+</div>
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