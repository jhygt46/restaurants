<?php
session_start();
date_default_timezone_set('America/Santiago');

require('admin/class/core_class.php');
$core = new Core();
//$core->is_local($_GET['id_loc']);
$code_verificado = false;
$id_loc = (is_numeric($_GET["id_loc"])) ? $_GET["id_loc"] : 0 ;

if($_SESSION['user']['info']['id_user'] > 0){
    
    $id_user = $_SESSION['user']['info']['id_user'];
    $user_local = $core->con->sql("SELECT * FROM fw_usuarios_locales WHERE id_loc='".$id_loc."' AND id_user='".$id_user."'");
    if($user_local['count'] == 1){
        
        $code_cookie = bin2hex(openssl_random_pseudo_bytes(30));
        setcookie('CODE', $code_cookie, time()+10800);
        setcookie('ID', $id_loc, time()+10800);
        $core->con->sql("UPDATE locales SET cookie_code='".$code_cookie."' WHERE id_loc='".$id_loc."'");
        $code_verificado = true;
        session_destroy();
        
    }
    if($user_local['count'] == 0){
        die("NO TIENE LOS PERMISOS NECESARIOS PARA INGRESAR AL PUNTO DE VENTA");
    }

}

if(isset($_COOKIE['CODE']) && strlen($_COOKIE['CODE']) == 60){
    
    if(!$code_verificado){
        $exist = $core->con->sql("SELECT * FROM locales WHERE cookie_code='".$_COOKIE["CODE"]."' AND id_loc='".$id_loc."'");
        if($exist['count'] == 0){
            die("BUENA NELSON.COM #2");
        }
    }
    
}

$info = $core->get_data('www.izusushi.cl');
$pedidos = $core->get_ultimos_pedidos(null);
$code = $core->socket_code($id_loc, $info['id_gir']);

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
        <script> 
            var local_code = '<?php echo $code; ?>'; 
            var pedidos = <?php echo json_encode($pedidos); ?>;
        </script>
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
                                <div class="preguntas">
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Repartidor</div>
                                        <div class="pre_check"><select><option value="0">Seleccionar</option><option value="1">Juan</option><option value="1">Maximiliano</option></select></div>
                                    </div>
                                    <div class="pregunta clearfix">
                                        <div class="pre_nom">Cancelar</div>
                                        <div class="pre_check"><input type="checkbox" id="cancelar" /></div>
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
                        <div class="n_info">
                            <div class="data_info"></div>
                        </div>
                        <div class="n_submit">
                            <input type="button" value="Enviar" onclick="done_pedido()" />
                        </div>
                    </div>
                    <div class="p3 nuevo_pedido vhalign" style="display: none">
                        <div class="np_close" onclick="np_close(this)"></div>
                        <div class="n_title"></div>
                        <div class="n_stitle"></div>
                        <div class="n_info">
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
                        <div class="n_info">
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
            </div>
        </div>
    </body>
</html>