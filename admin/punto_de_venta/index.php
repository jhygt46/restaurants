<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
esconder_index();
redireccion_ssl();
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();
$info = $core->get_data_pos();

$preguntas = ($info['pedido_wasabi'] == 1 || $info['pedido_gengibre'] == 1 || $info['pedido_palitos'] == 1 || $info['pedido_soya'] == 1 || $info['pedido_teriyaki'] == 1) ? true : false ;
$comentarios = ($info['pedido_comentarios'] == 1) ? true : false ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["nombre"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/default.ico' />
        <link rel="stylesheet" href="/css/reset.css" media="all" />
        <link rel="stylesheet" href="/css/pos_.css" media="all" />
        <link rel="stylesheet" href="/css/sweetalert.css" media="all" />
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="<?php echo $url["path"]; ?>data/<?php echo $_COOKIE["giro_code"]; ?>.js" type="text/javascript"></script>
        <script src="<?php echo $url["path"]; ?>js/pos_lista_.js" type="text/javascript"></script>
        <script src="<?php echo $url["path"]; ?>js/sweetalert.min.js" type="text/javascript"></script>
        <script>
            var aud = new Audio('/audios/<?php echo ($info['sonido'] == "") ? "Yes" : $info['sonido'] ; ?>.mp3');
            var tipo_comanda = <?php echo $info['pos']; ?>;
            var id = '<?php echo $info['id']; ?>';
            var local_lat = '<?php echo $info['lat']; ?>';
            var local_lng = '<?php echo $info['lng']; ?>';
            var ssl = '<?php echo $info['ssl']; ?>';
            var ip = '<?php echo $info['ip']; ?>';
            var dns = '<?php echo $info['dns']; ?>';
            var dominio = '<?php echo $info['dominio']; ?>';
            var tiempos = { retiro: <?php echo $info['t_retiro']; ?>, despacho: <?php echo $info['t_despacho']; ?> };
            var estados = [ <?php for($i=0; $i<count($info['estado']); $i++){ if($i>0){ echo ", "; } echo "'".$info['estado'][$i]."'";  } ?> ];
            var pedidos = <?php if($info["pedidos"] != null){ echo json_encode($info["pedidos"]); }else{ echo '[]'; } ?>;
            var motos = <?php if($info["motos"] != null){ echo json_encode($info["motos"]); }else{ echo '[]'; } ?>;
        </script>
    </head>
    <body onresize="resize()">
        <div class="contenedor">
            <div class="pop_up">
                <div class="cont_pop_up">
                    <div class="pop pop_dim_1 pop_cats vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1>Categorias</h1><h2>Seleccionar categoria</h2></div></div></div>
                            <div class="cont_info">
                                <div class="informacion"><div class="lista"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_pre vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1></h1><h2></h2></div></div></div>
                            <div class="cont_info">
                                <div class="informacion info_btn"><div class="lista"></div></div>
                            </div>
                            <div class="n_submit">
                                <div class="cont_submit">
        	                        <input class="vhalign" type="button" value="Enviar" onclick="confirmar_pregunta_productos(this)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_pedido vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1></h1><h2></h2></div></div></div>
                            <div class="cont_info">
                                <div class="informacion info_btn">

                                    <div class="pedidos_inputs">
                                        <input id="id_ped" type="hidden" value="0" />
                                        <input id="seleccionado" type="hidden" value="-1" />
                                        <input id="id_puser" type="hidden" value="0" />
                                        <input id="id_pdir" type="hidden" value="0" />
                                        <input id="lat" type="hidden" value="0" />
                                        <input id="lng" type="hidden" value="0" />
                                        <input id="calle" type="hidden" value="0" />
                                        <input id="num" type="hidden" value="0" />
                                        <input id="comuna" type="hidden" value="0" />
                                        <input id="costo" type="hidden" value="0" />
                                        <div class="cont_ped_input clearfix">
                                            <div class="tel">
                                                <span>Telefono: </span>
                                                <input id="telefono" type="tel" value="" onkeyup="telefono_keyup(this)" />
                                            </div>
                                            <div class="nom">
                                                <span>Nombre: </span>
                                                <input id="nombre" type="text" value="" />
                                            </div>
                                        </div>
                                        <div class="cont_ped_input">
                                            <div class="despacho">
                                                <span>Tipo: </span>
                                                <select id="despacho" onchange="change_despacho(this)"><option value="0">Retiro Local</option><option value="1">Despacho Domicilio</option></select>
                                            </div>
                                        </div>
                                        <div id="m_direccion" class="cont_ped_input clearfix">
                                            <div class="direccion">
                                                <span>Direccion: </span>
                                                <input id="direccion" type="text" />
                                            </div>
                                            <div class="depto">
                                                <span>Depto: </span>
                                                <input id="depto" type="text" />
                                            </div>
                                        </div>
                                        <div id="l_direccion" class="cont_ped_input">
                                            <div class="ttl_dir">Direcciones:</div>
                                            <div class="t_direcciones"></div>
                                        </div>
                                        <?php if($preguntas && $comentarios){ ?>
                                        <div class="cont_check_coment clearfix">
                                            <div class="cont_ped_input cont_ped_input1" style="width: 49%; float: left; padding-right: 1%; min-height: 80px">
                                                <?php if($info['pedido_wasabi'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Wasabi: </span>
                                                    <input type="checkbox" id="pre_wasabi" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_gengibre'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Gengibre: </span>
                                                    <input type="checkbox" id="pre_gengibre" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_soya'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Soya: </span>
                                                    <input type="checkbox" id="pre_soya" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_teriyaki'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Tariyaki: </span>
                                                    <input type="checkbox" id="pre_teriyaki" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_palitos'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Palitos: </span>
                                                    <select id="pre_palitos" class="valign">
                                                        <?php for($i=0; $i<10; $i++){ ?>
                                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <div class="cont_ped_input cont_ped_input2" style="width: 49%; float: left; padding-left: 1%">
                                                <div class="comentario" style="height: 100%">
                                                    <span style="height: 25px; padding-bottom: 0px">Comentarios: </span>
                                                    <Textarea style="height: calc(100% - 25px)" id="comentarios"></Textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if($preguntas && !$comentarios){ ?>
                                            <div class="cont_ped_input">
                                                <?php if($info['pedido_wasabi'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Wasabi: </span>
                                                    <input type="checkbox" id="pre_wasabi" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_gengibre'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Gengibre: </span>
                                                    <input type="checkbox" id="pre_gengibre" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_soya'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Soya: </span>
                                                    <input type="checkbox" id="pre_soya" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_teriyaki'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Tariyaki: </span>
                                                    <input type="checkbox" id="pre_teriyaki" class="valign">
                                                </div>
                                                <?php } ?>
                                                <?php if($info['pedido_palitos'] == 1){ ?>
                                                <div class="pregunta clearfix">
                                                    <span>Palitos: </span>
                                                    <select id="pre_palitos" class="valign">
                                                        <?php for($i=0; $i<10; $i++){ ?>
                                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php if(!$preguntas && $comentarios){ ?>
                                            <div class="cont_ped_input">
                                                <div class="comentario">
                                                    <span>Comentarios: </span>
                                                    <Textarea id="comentarios"></Textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="cont_ped_input clearfix">
                                            <div class="tipo_pago_1">
                                                <span>Tipo de Pago: </span>
                                                <select id="tipo_pago" onchange="change_tipo_pago(this)">
                                                    <option value="1">Efectivo</option>
                                                    <option value="2">Tarjeta de Debito</option>
                                                    <option value="3">Tarjeta de Credito</option>
                                                </select>
                                            </div>
                                            <div class="tipo_pago_2">
                                                <span>Monto: </span>
                                                <input type="text" id="monto" onkeyup="monto_keyup(this)" />
                                            </div>
                                            <div class="tipo_pago_3">
                                                <span>Vuelto: </span>
                                                <input type="text" id="vuelto" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="n_submit">
                                <div class="cont_submit">
        	                        <input class="vhalign" type="button" value="Enviar" onclick="done_pedido(this)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_detalle vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1>Detalle</h1><h2>Seleccionar categoria</h2></div></div></div>
                            <div class="cont_info">
                                <div class="informacion info_btn"><div class="lista"></div></div>
                            </div>
                            <div class="n_submit">
                                <div class="cont_submit">
                                    <div class="cont_total valign">
                                        <div class="sub"></div>
                                        <div class="total"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_pro_cat vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1></h1><h2></h2></div></div></div>
                            <div class="cont_info">
                                <div class="informacion info_btn"><div class="lista"></div></div>
                            </div>
                            <div class="n_submit">
                                <div class="cont_submit">
        	                        <input class="vhalign" type="button" value="Enviar" onclick="confirmar_productos_promo(true)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_mapa vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="cont_info" id="mapa_motos"></div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_setting vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1>Configuracion</h1></div></div></div>
                            <div class="cont_info">
                                <div class="informacion"><div class="lista"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="pop pop_dim_1 pop_chat vhalign">
                        <div class="cont_nuevo">
                            <div class="cerrar" onclick="np_close()"></div>
                            <div class="titulo"><div class="cont_titulo"><div class="ctitle valign"><h1>Mensajes</h1></div></div></div>
                            <div class="cont_info">
                                <div class="informacion"><div class="lista"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contenido">
                <div class="pedidos">
                    <div class="titulo_pedidos"><div class="cont_titulo"><div class="tt valign">Pedidos</div><div class="mas valign" onclick="ver_pedido(-1)"><div class="cont_m"><div class="m1"></div><div class="m2"></div></div></div></div></div>
                    <div class="lista_pedidos">
                        <div class="cont_lista"></div>
                    </div>
                    <div class="config_pedido"><div class="cont_config"><div class="icons vhalign"><div class="ic material-icons" onclick="btn_mapa()">my_location</div><div class="ic material-icons" onclick="btn_setting()">settings</div><div class="ic material-icons" onclick="btn_chat()">chat</div></div></div></div>
                </div>
                <div class="categorias">
                    <div class="titulo_categorias"><div class="cont_titulo"><div class="tt valign">Categorias</div></div></div>
                    <div class="lista_categorias">
                        <div class="cont_categorias"></div>
                    </div>
                </div>
                <div class="productos">
                    <div class="titulo_productos"><div class="cont_titulo"><div class="tt valign">Productos</div></div></div>
                    <div class="lista_productos">
                        <div class="cont_productos"></div>
                    </div>
                </div>
                <div class="configuracion">
                    <div class="ic material-icons" onclick="btn_mapa()">my_location</div>
                    <div class="ic material-icons" onclick="btn_setting()">settings</div>
                    <div class="ic material-icons" onclick="btn_chat()">chat</div>
                </div>
            </div>
            
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbKlHezhqgy7z57ipcJk8mDK4rf6drvjY&libraries=places&callback=init_map"></script>
    </body>
</html>