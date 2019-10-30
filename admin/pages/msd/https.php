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
$titulo = "Certificado SSL";
$accion = "solicitar_ssl";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
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
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="divImage clearfix">
                        <span><p>Costo Instalacion:</p></span>
                        <div class="btn_info"><div class="info">$19.990</div></div>
                    </label>
                    <label class="divHtml clearfix">
                        <span><p>Costo Mensual:</p></span>
                        <div class="btn_info"><div class="info">$1.990</div></div>
                    </label>
                    <label class="clearfix">
                        <span><p>Solicitar Certificado:</p></span>
                        <select id="solicitud">
                            <option value="0">No</option>
                            <option value="1">Si, deseo contratarlo</option>
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