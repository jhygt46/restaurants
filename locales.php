<?php
session_start();
date_default_timezone_set('America/Santiago');

require('admin/class/core_class.php');
$core = new Core();

$id_loc = (is_numeric($_GET["id_loc"])) ? $_GET["id_loc"] : 0 ;
$local = $core->local($id_loc);

if($id_loc != $_COOKIE['ID']){
    unset($_COOKIE['ID']);
    unset($_COOKIE['CODE']);
}

if(!isset($_COOKIE['CODE'])){
    
    if($local['op'] == 1){
        $code_cookie = bin2hex(openssl_random_pseudo_bytes(30));
        setcookie('CODE', $code_cookie, time()+50400);
        setcookie('ID', $id_loc, time()+50400);
        $core->con->sql("UPDATE locales SET cookie_code='".$code_cookie."' WHERE id_loc='".$id_loc."'");
        session_destroy();
    }else{
        die("ERROR 1: CONTACTARSE CON EL ADMINISTRADOR");
    }

}else{
    
    $exist = $core->con->sql("SELECT * FROM locales WHERE cookie_code='".$_COOKIE["CODE"]."' AND id_loc='".$id_loc."'");
    if($exist['count'] == 0){
        die("ERROR 2: CONTACTARSE CON EL ADMINISTRADOR");
    }
    
}

$info = $core->get_data($local['dominio']);
$pedidos = $core->get_ultimos_pedidos(null);
$info_local = $core->socket_code($id_loc, $info['id_gir']);
$motos = $core->get_repartidores_local($id_loc);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/locales.ico' />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info["font"]['family']; ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $info['path']; ?>/css/reset.css" media="all" />
        <link rel="stylesheet" href="<?php echo $info['path']; ?>/css/pos.css" media="all" />
        <link rel="stylesheet" href="<?php echo $info['path']; ?>/css/sweetalert.css" media="all" />
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="<?php echo $info['path']; ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="<?php echo $info['path']; ?>/js/data/<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script>
            //var aud = new Audio('audios/<?php echo $local['sonido']; ?>.mp3');
            var local_code = '<?php echo $info_local['code']; ?>';
            var local_lat = '<?php echo $info_local['lat']; ?>';
            var local_lng = '<?php echo $info_local['lng']; ?>';
            var dominio = '<?php echo $local['dominio']; ?>';
            var ssl = '<?php echo $info['ssl']; ?>';
            var tiempos = { retiro: <?php echo $local['t_retiro']; ?>, despacho: <?php echo $local['t_despacho']; ?> };
            var estados = [ <?php for($i=0; $i<count($info['estados']); $i++){ if($i>0){ echo ", "; } echo "'".$info['estados'][$i]."'";  } ?> ];
            var pedidos = <?php if($pedidos != null){ echo json_encode($pedidos); }else{ echo '[]'; } ?>;
            var motos = <?php if($motos != null){ echo json_encode($motos); }else{ echo '[]'; } ?>;
        </script>
        <script src="<?php echo $info['path']; ?>/js/pos_lista.js" type="text/javascript"></script>
        <script src="<?php echo $info['path']; ?>/js/sweetalert.min.js" type="text/javascript"></script>
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
                    <div class="p1 nuevo_pedido vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_stitle"></div>
                        <div class="n_info n1">
                            <div class="data_info">
                                <div class="nom_tel clearfix">
                                    <div class="nom">
                                        <span>Nombre: </span>
                                        <input id="nombre" type="text" />
                                    </div>
                                    <div class="tel">
                                        <span>Telefono: </span>
                                        <input id="telefono" type="tel" onkeyup="telefono_keyup(this)" />
                                    </div>
                                </div>
                                <div class="nom_tel clearfix">
                                    <div class="tipo">
                                        <span>Tipo: </span>
                                        <select id="despacho" onchange="change_despacho(this)"><option value="1">Despacho Domicilio</option><option value="0">Retiro Local</option></select>
                                    </div>
                                </div>
                                <div class="nom_tel t_direcciones">

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
                                    <div class="pregunta t_repartidor clearfix" style="padding-bottom: 10px">
                                        <div class="pre_nom" style="width: 50%">Repartidor</div>
                                        <div class="pre_check" style="width: 50%">
                                            <select id="id_mot">
                                                <option value="0">Sin Asignar</option>
                                                <?php for($i=0; $i<count($motos); $i++){ ?>
                                                    <option value="<?php echo $motos[$i]['id_mot']; ?>"><?php echo $motos[$i]['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
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
                                <div class="preguntas">
                                    <div class="pregunta clearfix">
                                        <div onclick="eliminar('Eliminar', 1, 'eliminarlo')" class="btn_pedido" style="padding-right: 1%"><div style="background: #f00" class="btn_ped">Eliminar</div></div>
                                        <div onclick="eliminar('Ocultar', 0, 'ocultarlo')" class="btn_pedido" style="padding-left: 1%"><div style="background: #000" class="btn_ped">Ocultar</div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="n_submit">
                            <input type="button" value="Enviar" onclick="done_pedido()" />
                        </div>
                    </div>
                    <div class="p2 nuevo_pedido vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_info n2">
                            <div class="data_info"></div>
                        </div>
                    </div>
                    <div class="p3 nuevo_pedido vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_stitle"></div>
                        <div class="n_info n1">
                            <div class="data_info"></div>
                        </div>
                        <div class="n_submit">
                            <input type="button" value="Enviar" onclick="confirmar_productos_promo(this)" />
                        </div>
                    </div>
                    <div class="p4 nuevo_pedido vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_stitle"></div>
                        <div class="n_info n1">
                            <div class="data_info"></div>
                        </div>
                        <div class="n_submit">
                            <input type="button" value="Enviar" onclick="confirmar_pregunta_productos(this)" />
                        </div>
                    </div>
                    <div class="p5 map vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_info n1">
                            <div id="mapa_motos" style="height: 100%; width: 100%">
                            
                            </div>
                        </div>
                    </div>
                    <div class="p6 nuevo_pedido vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_stitle"></div>
                        <div class="n_info n1">
                            <div class="data_info"></div>
                        </div>
                        <div class="n_submit">
                            <input type="button" value="Enviar" onclick="confirmar_pregunta_productos(this)" />
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
                <div class="configuracion" style="width: 64px; float: left; margin-left: 6px; position: relative">
                    <div onclick="ver_motos_mapa()"><img src="images/local_config.jpg" alt="" /></div>
                    <div onclick="ver_opciones_pos()"><img src="images/local_config.jpg" alt="" /></div>
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbKlHezhqgy7z57ipcJk8mDK4rf6drvjY&libraries=places&callback=init_map"></script>
    </body>
</html>