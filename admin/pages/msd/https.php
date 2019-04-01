<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = "C:/AppServ/www/restaurants";
}else{
    $path = "/var/www/html/restaurants";
}

require_once($path."/admin/class/core_class.php");
$fireapp = new Core();


/* CONFIG PAGE */
$titulo = "Certificado SSL";
$accion = "solicitar_ssl";
/* CONFIG PAGE */

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
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>