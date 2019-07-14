<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();


/* CONFIG PAGE */
$titulo = "Usuarios";
$titulo_list = "Mis Usuarios";
$sub_titulo1 = "Ingresar Usuario";
$sub_titulo2 = "Modificar Usuario";
$accion = "crear_usuarios_local";

$eliminaraccion = "eliminar_usuario_local";
$id_list = "id_user";
$eliminarobjeto = "Usuario";
$page_mod = "pages/msd/usuarios_local.php";
/* CONFIG PAGE */

$id_user = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sub_titulo = $sub_titulo1;
$list = $core->get_usuarios_local($_GET["id_loc"]);
$m_locales = false;

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){

    $id_loc = $_GET["id_loc"];
    if(isset($_GET["id_user"]) && is_numeric($_GET["id_user"]) && $_GET["id_user"] != 0){
        $id_user = $_GET["id_user"];
        $that = $core->get_usuario($id_user);
        $sub_titulo = $sub_titulo2;
    }
}
?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/locales.php')"></li>
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
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="v_nombre" autocomplete="off" name="v_nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Correo:</p></span>
                        <input id="v_correo" autocomplete="off" name="v_correo" class="inputs" type="text" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="v_tipo" name="v_tipo">
                            <option value="0" <?php if($that['tipo'] == 0){ echo "selected"; } ?>>Punto de Venta</option>
                            <option value="1" <?php if($that['tipo'] == 1){ echo "selected"; } ?>>Cocina</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Borrar Direcciones:</p></span>
                        <input id="borrar_direcciones" type="checkbox" class="checkbox" value="1" <?php if($that['item_pagina'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Password:</p></span>
                        <input id="v_pass1" autocomplete="off" name="v_pass1" class="inputs" type="password" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Confirmacion Password:</p></span>
                        <input id="v_pass2" autocomplete="off" name="v_pass2" class="inputs" type="password" value="" require="" placeholder="" />
                    </label>
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
                    $tipo = $list[$i]['tipo'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <!--<a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>-->
                        <a class="icono ic7" onclick="navlink('pages/msd/configurar_usuarios_local.php?id_user=<?php echo $id; ?>&id_loc=<?php echo $id_loc; ?>')" title="Configurar Usuario Local"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_user=<?php echo $id; ?>&id_loc=<?php echo $id_loc; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>