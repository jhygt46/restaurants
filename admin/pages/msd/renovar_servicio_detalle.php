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
$monto = $core->get_monto();

if($_GET["pago"] == 2){
    $monto = $monto * 5.5;
}
if($_GET["pago"] == 3){
    $monto = $monto * 10;
}

echo "((".$monto."))";

?>

<div class="pagina">
    <div class="title">
        <h1>Pago por transferencia electronica</h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/renovar_servicio.php')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items" style="padding-bottom: 10px"><h1>Datos para transferencia electronica</h1></div>
                
            </div>
        </div>
    </div>
</div>