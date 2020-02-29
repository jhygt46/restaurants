<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
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

$width = 500;
$alto = intval($width * $core->get_alto() / 100);

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
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_locales', '<?php echo $id_loc; ?>', 'Local', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <label class="clearfix">
                        <span><p>Sonido:</p></span>
                        <select id="sonido">
                            <option value="">Seleccionar</option>
                            <?php for($i=0; $i<count($sonidos); $i++){ ?>
                            <option value="<?php echo $sonidos[$i]; ?>" <?php if($sonidos[$i] == $that["sonido"]){ ?>selected<?php } ?>><?php echo $sonidos[$i]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Tiempo Retiro: (minutos)</p></span>
                        <input id="t_retiro" type="text" class="inputs" value="<?php echo $that['t_retiro']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tiempo Despacho: (minutos)</p></span>
                        <input id="t_despacho" type="text" class="inputs" value="<?php echo $that['t_despacho']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Punto de Venta:</p></span>
                        <select id="pos">
                            <option value="0" <?php if($that["pos"] == 0){ ?>selected<?php } ?>>Ver Pedido</option>
                            <option value="1" <?php if($that["pos"] == 1){ ?>selected<?php } ?>>Imprimir y Cerrar Pedido</option>
                            <option value="2" <?php if($that["pos"] == 2){ ?>selected<?php } ?>>Nada</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Imagen: (<?php echo $width; ?>x<?php echo $alto; ?>)</p></span>
                        <input style="padding-top: 6px" id="file_image0" type="file" />
                    </label>
                    <label class="clearfix">
                        <span><p>Enviar Correo:</p></span>
                        <input id="activar_envio" type="checkbox" class="checkbox" value="1" <?php if($that['activar_envio'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>