<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="images/fire.ico" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/sweetalert.min.js"></script>
        <script type="text/javascript" src="js/base_1.js"></script>
        <script type="text/javascript" src="js/form_1.js"></script>
        <script type="text/javascript" src="js/maps.js"></script>
        <link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
        <link rel="stylesheet" href="css/sweetalert.css" type="text/css" media="all">
        <link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
        <link rel="stylesheet" href="css/jquery-ui.css" type="text/css" media="all">
    </head>
    <body>
        <div class="contenedor relative">
            <div class="modals">
                <div class="relative">
                    
                    <div class="modal_perfil cont_modal vhalign">
                        <div class="relative">
                            <div class="close" onclick="closes(this)"></div>
                        </div>
                    </div>
                    <div class="modal_error cont_modal vhalign">
                        <div class="relative">
                            <div class="close" onclick="closes(this)"></div>
                        </div>
                    </div>
                    <div class="modal_loading cont_modal vhalign">
                        <div class="relative">
                            <div class="close" onclick="closes(this)"></div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="sitio">
                <div class="relative">
                    
                    <div class="menu_top">
                        <div class="relative">
                            <div class="btn_menu valign" onclick="menu_toggle()"></div>
                            <div class="btn_perfil valign" onclick="open_perfil()"></div> 
                            <div class="logo clearfix">
                                <div class="logo_img"></div>
                                <div class="logo_txt">Mi Sitio Delivery</div>
                            </div>
                        </div>
                    </div>
                    <div class="menu_left">
                        <div class="cont_menu relative">
                            <div class="menu">
                                
                                <div class="bloque">
                                    <div class="titulo" onclick="open_bloque(this)">
                                        <div class="icono ic1"></div>
                                        <div class="texto">Mi Cuenta</div>
                                    </div>
                                    <ul class="bloque_lista">
                                        <li onclick="navlink('pages/msd/inicio.php')">Inicio<p class="valign">3</p></li>
                                        <li onclick="navlink('pages/msd/giros.php')">Crear Giros</li>
                                        <li onclick="navlink('pages/msd/graficos.php')">Graficos</li>
                                    </ul>
                                </div>
                                
                            </div>                            
                        </div>
                    </div>
                    <div class="contenido">
                        <div class="cont_contenido relative">
                            <div class="html">
                                
                                <?php //require 'pages/msd/inicio.php'; ?>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq6hw0biMsUBdMBu5l-bai9d3sUI-f--g&libraries=places" async defer></script>
    </body>
</html>