<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();

/* CONFIG PAGE */
$sub_titulo = "Modificar Configuracion";
$accion = "configurar_estilos";
/* CONFIG PAGE */

$that = $core->get_giro();
$titulo = "Configuracion ".$that["nombre"];
$css = $core->get_css();
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php?id_gir=<?php echo $_SESSION['user']['id_gir']; ?>')"></li>
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
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Google Font Family:</p></span>
                        <input id="font-family" class="inputs" type="text" value="<?php echo $that['font_family']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Google Font Css:</p></span>
                        <input id="font-css" class="inputs" type="text" value="<?php echo $that['font_css']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Css Tipo Pagina:</p></span>
                        <select id="css_page">
                            <option value="">Seleccionar</option>
                            <?php 
                                foreach($css as $value){ 
                                    if($value['tipo'] == 1){
                                        $sel='';
                                        if($value['archivo'] == $that['style_page']){ $sel='selected'; }
                                        echo '<option value="'.$value['archivo'].'" '.$sel.'>'.$value['nombre'].'</option>';
                                    } 
                                }
                            ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Css Colores:</p></span>
                        <select id="css_color">
                            <option value="">Seleccionar</option>
                            <?php 
                                foreach($css as $value){ 
                                    if($value['tipo'] == 2){
                                        $sel='';
                                        if($value['archivo'] == $that['style_color']){ $sel='selected'; }
                                        echo '<option value="'.$value['archivo'].'" '.$sel.'>'.$value['nombre'].'</option>';
                                    } 
                                }
                            ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Css Tipo Letra:</p></span>
                        <select id="css_modal">
                            <option value="">Seleccionar</option>
                            <?php 
                                foreach($css as $value){ 
                                    if($value['tipo'] == 3){
                                        $sel='';
                                        if($value['archivo'] == $that['style_modal']){ $sel='selected'; }
                                        echo '<option value="'.$value['archivo'].'" '.$sel.'>'.$value['nombre'].'</option>';
                                    } 
                                }
                            ?>
                        </select>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>