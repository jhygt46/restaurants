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
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_local";
/* CONFIG PAGE */

$id_cae = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){
    
    $id_loc = $_GET["id_loc"];
    $that = $fireapp->get_local($id_loc);
    
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
                    <input id="id_cae" type="hidden" value="<?php echo $id_cae; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Accion:</p></span>
                        <div class="btn_borrar"><div class="btn" onclick="eliminar('eliminar_locales', '<?php echo $id_loc; ?>', 'Local', '<?php echo $that['nombre']; ?>')">Eliminar</div></div>
                    </label>
                    <label class="clearfix">
                        <span><p>Sonido:</p></span>
                        <select id="sonido">
                            <option value="Applause.mp3">Applause</option>
                            <option value="Aww.mp3">Aww</option>
                            <option value="Ba-dum-tss.mp3">Ba-dum-tss</option>
                            <option value="Beep.mp3">Beep</option>
                            <option value="Beep_set.mp3">Beep_set</option>
                            <option value="Boo.mp3">Boo</option>
                            <option value="Breaking_glass.mp3">Breaking_glass</option>
                            <option value="City.mp3">City</option>
                            <option value="Crash.mp3">Crash.mp3</option>
                            <option value="DJ.mp3">DJ</option>
                            <option value="Fail.mp3">Fail</option>
                            <option value="Gunshot.mp3">Gunshot</option>
                            <option value="Happy_Birthday.mp3">Happy_Birthday</option>
                            <option value="Heartbeat.mp3">Heartbeat</option>
                            <option value="Ka-ching.mp3">Ka-ching</option>
                            <option value="Keyboard.mp3">Keyboard</option>
                            <option value="Laugh_track.mp3">Laugh_track</option>
                            <option value="Nooo.mp3">Nooo</option>
                            <option value="Rain_and_thunder.mp3">Rain_and_thunder</option>
                            <option value="Shutter.mp3">Shutter</option>
                            <option value="Whip.mp3">Whip</option>
                            <option value="White_noise.mp3">White_noise</option>
                            <option value="Yeehaw.mp3">Yeehaw</option>
                            <option value="Yes.mp3">Yes</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Tiempo Retiro:</p></span>
                        <input id="t_retiro" type="text" class="inputs" value="<?php echo $that['t_retiro']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tiempo Despacho:</p></span>
                        <input id="t_despacho" type="text" class="inputs" value="<?php echo $that['t_despacho']; ?>" require="" placeholder="" />
                    </label>
                    <label style="padding-top: 10px">
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>