<?php

require('admin/class/core_class.php');
$core = new Core();

if(isset($_GET['param_dom'])){
    $info = $core->get_data($_GET['param_dom']);
}else{
    $info = $core->get_data('www.izusushi.cl');
}
$core->get_web_js_data(1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=<?php echo $info["font"]['family']; ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_style"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_color"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_modals"]; ?>" media="all" />
        <script src="<?php echo $info["js_jquery"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_html"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_html_func"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_base"]; ?>" type="text/javascript"></script>
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
                    <div class="btn_toogle color_font_01 material-icons" onclick="tooglemenu()">view_headline</div>
                    <div class="menu_info">
                        <h1>Men&uacute;</h1>
                        <ul class="lista_paginas">
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pagina">
                <div class="header color_back_03 <?php echo ($info["header_fixed"] == 1) ? 'fixed' : ''; ?>">
                    <div class="header_logo vhalign"><img src="/images/logos/<?php echo $info["logo"]; ?>" alt="" /></div>
                    <div class="menu_right" onclick="open_carro()"><div class="shop color_font_01 material-icons">shopping_cart</div><div class="cantcart color_back_02"><div class="cantcart_num vhalign size_font_02 color_font_02">15</div></div></div>
                </div>
                <div class="contenido color_back_01">
                    <div class="cont_contenido"></div>
                </div>
                <div class="footer color_back_03 <?php echo ($info["footer_fixed"] == 1) ? 'fixed' : ''; ?>"></div>
            </div>
            <div class="modals">
                <div class="cont_modals">
                    <div class="modal vhalign hide modal_pagina">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03"></h1><h2 class="size_font_02 color_font_02"></h2></div></div>
                            <div class="close"></div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_carta">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03"></h1><h2 class="size_font_02 color_font_02"></h2></div></div>
                            <div class="close"></div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_carro">
                        <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03">Haz tu Pedido</h1><h2 class="size_font_02 color_font_02">Verifica que esten todos tu productos</h2></div></div>
                            <div class="close"></div>
                            <div class="cont_info">
                                <div class="info_modal carro_inicio"></div>
                                <div class="info_modal carro_direccion hide">
                                    <div class="cont_direccion" style="height: 100%">
                                        <div class="direccion_opciones">
                                            <div onclick="show_retiro()">Retiro en Local</div>
                                            <div onclick="show_despacho()">Despacho a Domicilio</div>
                                        </div>
                                        <div class="direccion_op1 hide">
                                            <div>Local Providencia</div>
                                        </div>
                                        <div class="direccion_op2 hide" style="height: 100%">
                                            <input type="text" id="pac-input" style="margin: 1%; width: 97%; height: 40px" placeholder="Ingrese su direccion y numero" />
                                            <div id="map_direccion" style="height: 100%; background: #f00"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="info_modal carro_final hide">
                                    FIN
                                </div>
                                <div class="info_modal carro_seguimiento hide">
                                    SEGUIMIENTO
                                </div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_pedido()" type="button" value="Confirmar" />
                            </div>
                        </div>
                    </div>
                    <div class="modal vhalign hide modal_productos_promo">
                        <div class="cont_modal">
                            <div class="cont_modal">
                            <div class="titulo"><div class="cont_titulo valign"><h1 class="size_font_03 color_font_03"></h1><h2 class="size_font_02 color_font_02"></h2></div></div>
                            <div class="close"></div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
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
                            <div class="close"></div>
                            <div class="cont_info">
                                <div class="info_modal"></div>
                            </div>
                            <div class="acciones">
                                <input class="confirmar" onclick="confirmar_pregunta_productos(this)" type="button" value="Confirmar" />
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbKlHezhqgy7z57ipcJk8mDK4rf6drvjY&libraries=places" async defer></script>
    </body>
</html>