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

?>

<div class="pagina">
    <div class="title">
        <h1>Renovación del Servicio</h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items"><h1>Selecciona Plazos de Pago</h1></div>
                <div class="planes clearfix">
                    <div class="plan"><div class="detalleplan"></div></div>
                    <div class="plan"><div class="detalleplan"></div></div>
                    <div class="plan"><div class="detalleplan"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>