<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();


/* CONFIG PAGE */
$titulo = "Usuarios";
$titulo_list = "Mis Usuarios";
$sub_titulo1 = "Ingresar Usuario";
$sub_titulo2 = "Modificar Usuario";
$accion = "crear_usuario";

$eliminaraccion = "eliminar_usuario";
$id_list = "id_user";
$eliminarobjeto = "Usuario";
$page_mod = "pages/msd/usuarios.php";
/* CONFIG PAGE */

$id_user = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sub_titulo = $sub_titulo1;
$list = $fireapp->get_usuarios();
$list_loc = $fireapp->get_locales();
$list_giros = $fireapp->get_giros();
$inicio = $fireapp->inicio();
$m_locales = false;

echo "<pre>";
print_r($list);
echo "</pre>";

if(isset($_GET["id_user"]) && is_numeric($_GET["id_user"]) && $_GET["id_user"] != 0){

    $id_user = $_GET["id_user"];
    $that = $fireapp->get_usuario($id_user);
    $sub_titulo = $sub_titulo2;
    if($that['tipo'] == 2){ $m_locales = true; }

}
?>
<script>
    function ver_locales(){
        var value = $('#tipo').val();
        if(value == 1){
            $('.locales').hide();
            $('.giros').show();
        }
        if(value == 2){
            $('.locales').show();
            $('.giros').hide();
        }
        if(value == 3 || value == 4){
            $('.giros').hide();
            $('.locales').hide();
        }
    }
</script>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <form action="" method="post">
                <div class="form_titulo clearfix">
                    <div class="titulo"><?php echo $sub_titulo; ?></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <fieldset class="<?php echo $class; ?>">
                    <input id="id" type="hidden" value="<?php echo $id_user; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Correo:</p></span>
                        <input id="correo" class="inputs" type="text" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo" onchange="ver_locales()">
                            <?php if($inicio["id_gir"] != 0){ ?>   
                                <option value="1">Administrador</option>
                            <?php } ?>
                            <?php if($inicio["id_gir"] != 0 && $inicio["re_venta"] == 0){ ?>
                                <option value="2">Solo Punto de Venta</option>
                            <?php } ?>
                            <?php if($inicio["id_user"] == 1 || $inicio["re_venta"] == 1){ ?>
                                <option value="3">Vendedor</option>
                            <?php } ?>

                            <?php if($inicio["id_user"] == 1){ ?>    
                                <option value="4">Reclutador</option>
                            <?php } ?>
                            
                        </select>
                    </label>
                    <label class="locales clearfix" style="display:<?php if($m_locales){?>block<?php }else{ ?>none<?php } ?>">
                        <span><p>Locales:</p></span>
                        <div class="perfil_preguntas">
                            <?php foreach($list_loc as $value){ ?>
                                <div class="clearfix">
                                    <input style="margin-top: 4px; width: 18px; height: 18px; float: left" id="local-<?php echo $value['id_loc']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
                                    <div style="font-size: 18px; padding-left: 4px; float: left" class='detail'><?php echo $value['nombre']; ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </label>
                    <?php if($inicio["id_user"] == 1){ ?>
                    <label class="giros clearfix">
                        <span><p>Giro:</p></span>
                        <select id="giro">
                            <option value="0">Seleccione</option>
                            <?php foreach($list_giros as $value){ $check = ''; if($inicio["id_gir"] == $value['id_gir']){ $check = 'selected'; } ?>
                                <option value="<?php echo $value['id_gir']; ?>" <?php echo $check; ?>><?php echo $value['dominio']; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <?php }else{ ?>
                        <div class="giros"></div>
                    <?php } ?>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo"><h1><?php echo $titulo_list; ?></h1></div>
                <ul class="opts clearfix">
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_user=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>