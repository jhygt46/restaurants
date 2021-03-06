<?php
    
    esconder("inicio.php");
    require_once $url["dir"]."admin/class/core_class_prod.php";
    $core = new Core();
    $inicio = $core->get_info_cookie();
    /*
    echo "<pre>";
    print_r($inicio);
    echo "</pre>";
    */
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo $url["path"]; ?>images/favicon/' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $url['path']; ?>admin/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo $url['path']; ?>js/sweetalert.min.js"></script>
        <script type="text/javascript" src="<?php echo $url['path']; ?>admin/js/base_1.js"></script>
        <script type="text/javascript" src="<?php echo $url['path']; ?>admin/js/form_1.js"></script>
        <script type="text/javascript" src="<?php echo $url['path']; ?>admin/js/maps.js"></script>
        <link rel="stylesheet" href="<?php echo $url['path']; ?>admin/css/reset.css" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo $url['path']; ?>admin/css/sweetalert.css" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo $url['path']; ?>admin/css/layout.css" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo $url['path']; ?>admin/css/jquery-ui.css" type="text/css" media="all">
    </head>
    <body>
        <div class="contenedor relative">
            <div class="modals">
                <div class="relative">
                    <div class="modal_perfil cont_modal vhalign">
                        <div class="cont_relative">
                            <div class="close" onclick="closes(this)"></div>
                            <div class="mo_content">
                                <div class="mo_cont">
                                    <ul class="clearfix">
                                        <li class="foto"><img src="images/no-user.png" alt="" /></li>
                                        <li class="info">
                                            <div class="cont_info">
                                                <h2><?php echo $inicio['nombre']; ?></h2>
                                                <h3><?php echo $inicio['correo']; ?></h3>
                                                <a href="?accion=logout">Salir</a>
                                            </div>
                                        </li>
                                    </ul> 
                                    
                                </div>
                            </div>
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
                                        <li onclick="navlink('pages/msd/ver_giro.php')">Inicio<!--<p class="valign">3</p>--></li>
                                        <?php if($inicio['admin'] == 1){ ?><li onclick="navlink('pages/msd/giros.php')">Giros</li><?php } ?>
                                        <?php if($inicio['admin'] == 1){ ?><li onclick="navlink('pages/msd/cuentas.php')">Cuentas</li><?php } ?>
                                        <?php if($inicio['admin'] == 1){ ?><li onclick="navlink('pages/msd/ver_disponibilidad.php')">Disponibilidad</li><?php } ?>
                                    </ul>
                                </div>
                                <?php if($inicio['id_user'] == 1 || $inicio['re_venta'] == 1){ ?>
                                <div class="bloque">
                                    <div class="titulo" onclick="open_bloque(this)">
                                        <div class="icono ic1"></div>
                                        <div class="texto">Administrador</div>
                                    </div>
                                    <ul class="bloque_lista">
                                        <?php if($inicio['id_user'] == 1 || $inicio['re_venta'] == 1){ ?><li onclick="navlink('pages/msd/usuarios.php')">Usuarios</li><?php } ?>
                                        <?php if($inicio['id_user'] == 1){ ?><li onclick="navlink('pages/msd/panel.php')">Panel de Control</li><?php } ?>
                                    </ul>
                                </div>
                                <?php } ?>
                                <?php if($inicio['id_user'] == 1){ ?>
                                <div class="bloque">
                                    <div class="titulo" onclick="open_bloque(this)">
                                        <div class="icono ic1"></div>
                                        <div class="texto">Pagos</div>
                                    </div>
                                    <ul class="bloque_lista">
                                        <?php if($inicio['id_user'] == 1){ ?><li onclick="navlink('pages/msd/pagos.php')">Pagos</li><?php } ?>
                                        <?php if($inicio['id_user'] == 1){ ?><li onclick="navlink('pages/msd/pagar_proveedores.php')">Pagar Proveedores</li><?php } ?>
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>                            
                        </div>
                    </div>
                    <div class="contenido">
                        <div class="cont_contenido relative">
                            <div class="html">
                                <?php

                                    if($inicio['admin'] == 1){
                                        require 'pages/msd/giros.php';
                                    }
                                    if($inicio['admin'] == 0){
                                        require 'pages/msd/ver_giro.php';
                                    }

                                ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq6hw0biMsUBdMBu5l-bai9d3sUI-f--g&libraries=places" async defer></script>
    </body>
</html>