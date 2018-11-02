<?php

require('admin/class/core_class.php');
$core = new Core();

if(isset($_GET['param_dom'])){
    $info = $core->get_data($_GET['param_dom']);
}else{
    $info = $core->get_data();
}

$js = $core->get_web_js_data2($info['id_gir']);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info["font"]['family']; ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_style"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_color"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_modals"]; ?>" media="all" />
        <script src="http://35.196.220.197/socket.io/socket.io.js"></script>
        <script src="<?php echo $info["js_jquery"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <!--<script src="<?php echo $info["js_html"]; ?>" type="text/javascript"></script>-->
        <script src="<?php echo $info["js_html_func"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_base"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_base_lista"]; ?>" type="text/javascript"></script>
        <style>
            body{
                font-family: <?php echo $info["font"]['css']; ?>;
            }
        </style>
</head>
    <body class="style_page_1">
        <div class="contenedor">
            <div class="menu_left">
                <div class="cont_menu_left">
                    <div class="btn_toogle material-icons" onclick="tooglemenu()">view_headline</div>
                    <div class="menu_info">
                        <h1>Men&uacute;</h1>
                        <ul class="lista_paginas">
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pagina">
                <div class="cont_pagina">
                    <div class="header <?php echo ($info["header_fixed"] == 1) ? 'fixed' : ''; ?>">
                        <div class="header_logo vhalign"><img src="/images/logos/<?php echo $info["logo"]; ?>" alt="" /></div>
                        <div class="menu_right" onclick="open_carro()"><div class="shop material-icons">shopping_cart</div><div class="cantcart"><div class="cantcart_num vhalign">15</div></div></div>
                    </div>
                    <div class="contenido">
                        <div class="cont_contenido <?php echo ($info["footer_fixed"] == 1) ? 'padding_cont_f1' : 'padding_cont_f2'; ?>"></div>
                    </div>
                    <div class="footer <?php echo ($info["footer_fixed"] == 1) ? 'fixed' : ''; ?>"></div>
                </div>
            </div>
            <div class="modals">
                <div class="cont_modals">
                    <div class="modal vhalign hide modal_pagina">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03"></h1><h2 class="size_font_02 color_font_02"></h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_carta">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="buena"></h1><h2 class=""></h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_productos_promo">
                        <div class="cont_modal">
                            <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03"></h1><h2 class="size_font_02 color_font_02"></h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_productos_promo(this)" type="button" value="Confirmar" />
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_pregunta_productos">
                        <div class="cont_modal">
                            <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03"></h1><h2 class="size_font_02 color_font_02"></h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_pregunta_productos(this)" type="button" value="Confirmar" />
                            </div>
                        </div>
                        </div>
                    </div>

                    
                    
                    <!-- MODAL CARRO 01 -->
                    <div class="modal vhalign hide modal_carro paso_01">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="">Haz tu Pedido</h1><h2 class="">Verifica que esten todos tu productos</h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="paso_2()" type="button" value="Siguiente" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal vhalign hide modal_carro paso_02">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="">Haz tu Pedido</h1><h2 class="">Verifica que esten todos tu productos</h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="height: calc(100% - 67px); padding-bottom: 0px">
                                    <div class="cont_direccion">
                                        <div class="direccion_opciones">
                                            <div class="dir_op" onclick="show_retiro()"><div class="title">Retiro en Local</div><div class="stitle">Sin Costo</div></div>
                                            <div class="dir_op" onclick="show_despacho()"><div class="title">Despacho a Domicilio</div><div class="stitle">Desde $1.000</div></div>
                                        </div>
                                        <div class="direccion_op1 hide">
                                            <?php for($i=0; $i<count($info['locales']); $i++){ ?>
                                            <div class="dir_locales">
                                                <div class="cont_local clearfix">
                                                    <div class="local_info" onclick="select_local(<?php echo $info['locales'][$i]['id_loc']; ?>, '<?php echo $info['locales'][$i]['nombre']; ?>')">
                                                        <div class="title"><?php echo $info['locales'][$i]['nombre']; ?></div>
                                                        <div class="stitle"><?php echo $info['locales'][$i]['direccion']; ?></div>
                                                    </div>
                                                    <div class="local_mapa" onclick="map_local(<?php echo $info['locales'][$i]['id_loc']; ?>, <?php echo $info['locales'][$i]['lat']; ?>, <?php echo $info['locales'][$i]['lng']; ?>)">
                                                        <div class="icon_mapa"></div>
                                                    </div>
                                                </div>
                                                <div id="lmap-<?php echo $info['locales'][$i]['id_loc']; ?>" class="lmap"></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="direccion_op2 hide">
                                            <input type="text" id="pac-input" placeholder="Ingrese su direccion y numero" />
                                            <div id="map_direccion" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="acciones acc_paso2" style="display: none">
                                <input class="confirmar" onclick="paso_3()" type="button" value="Siguiente" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal vhalign hide modal_carro paso_03">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="">Haz tu Pedido</h1><h2 class="">Verifica que esten todos tu productos</h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal" style="padding-bottom: 57px">
                                    
                                    <div class="cont_final">
                                        <div class="final_section">
                                            <div class="fs_inputs fs_dire clearfix">
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Direccion</div>
                                                    <div class="fsin_in render_dir" style="padding-top: 5px; font-size: 12px"></div>
                                                </div>
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Depto</div>
                                                    <div class="fsin_in"><input type="text" class="pedido_depto" /></div>
                                                </div>
                                            </div>
                                            <div class="fs_inputs clearfix">
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Nombre</div>
                                                    <div class="fsin_in"><input type="text" class="pedido_nombre" /></div>
                                                </div>
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Telefono</div>
                                                    <div class="fsin_in"><input type="text" class="pedido_telefono" /></div>
                                                </div>
                                            </div>
                                            <div class="fs_inputs2 clearfix">
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Gengibre</div>
                                                </div>
                                                <div class="fs_in">
                                                    <div class="fsin_in"><input type="checkbox" value="1" id="pedido_gengibre" /></div>
                                                </div>
                                            </div>
                                            <div class="fs_inputs2 clearfix">
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Wasabi</div>
                                                </div>
                                                <div class="fs_in">
                                                    <div class="fsin_in"><input type="checkbox" value="1" id="pedido_wasabi" /></div>
                                                </div>
                                            </div>
                                            <div class="fs_inputs2 clearfix">
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Sushi para Embarazadas</div>
                                                </div>
                                                <div class="fs_in">
                                                    <div class="fsin_in"><input type="checkbox" value="1" id="pedido_embarazadas" /></div>
                                                </div>
                                            </div>
                                            <div class="fs_inputs3 clearfix">
                                                <div class="fs_in">
                                                    <div class="fsin_ttl">Palitos</div>
                                                </div>
                                                <div class="fs_in">
                                                    <div class="fsin_in"><select id="pedido_palitos"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div>
                                                </div>
                                            </div>
                                            <div class="fin_detalle">
                                                <div class="fin_pedido clearfix"><div class="fin_dll_price"></div><div class="fin_dll_name">PEDIDO :</div></div>
                                                <div class="fin_despacho clearfix"><div class="fin_dll_price"></div><div class="fin_dll_name">DESPACHO :</div></div>
                                                <div class="fin_total clearfix"><div class="fin_dll_price"></div><div class="fin_dll_name">TOTAL :</div></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" id="enviar_cotizacion" onclick="paso_4()" type="button" value="Finalizar" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal vhalign hide modal_carro paso_04">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="">Haz tu Pedido</h1><h2 class="">Verifica que esten todos tu productos</h2></div></div>
                            <div class="close material-icons">close</div>
                            <div class="cont_info">
                                <div class="info_modal_pedido" style="padding-bottom: 57px">
                                    
                                    <div class="nombre">PEDIDO #457</div>
                                    <div class="estado">Estado: Recepcionado</div>
                                    <div class="posicion" id="mapa_posicion"></div>
                                    <div class="tiempo">Tiempo estimado: 25 minutos</div>
                                    
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="nuevo_pedido()" type="button" value="Hacer Nuevo Pedido" />
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbKlHezhqgy7z57ipcJk8mDK4rf6drvjY&libraries=places" async defer></script>
    </body>
</html>