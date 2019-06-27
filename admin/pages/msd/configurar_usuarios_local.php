<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_local";
/* CONFIG PAGE */

$id_cae = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sonidos = ["Applause", "Aww", "Ba-dum-tss", "Beep", "Beep_set", "Boo", "Breaking_glass", "City", "Crash", "DJ", "Fail", "Gunshot", "Happy_Birthday", "Heartbeat", "Ka-ching", "Keyboard", "Laugh_track", "Nooo", "Rain_and_thunder", "Shutter", "Whip", "White_noise", "Yeehaw", "Yes"];

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){
    
    $id_loc = $_GET["id_loc"];
    $that = $core->get_local($id_loc);

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
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Accion:</p></span>
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_usuario_local', '<?php echo $id_user; ?>', 'Usuario', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <label class="clearfix">
                        <span><p>Pedidos Web:</p></span>
                        <select id="pos">
                            <option value="0" <?php if($that["pos"] == 0){ ?>selected<?php } ?>>No Modificar</option>
                            <option value="1" <?php if($that["pos"] == 1){ ?>selected<?php } ?>>Si Modificar</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Pedidos Punto de Venta:</p></span>
                        <select id="pos">
                            <option value="0" <?php if($that["pos"] == 0){ ?>selected<?php } ?>>No Modificar</option>
                            <option value="1" <?php if($that["pos"] == 1){ ?>selected<?php } ?>>Si Modificar</option>
                        </select>
                    </label>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>