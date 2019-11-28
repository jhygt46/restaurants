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
            <li class="back" onclick="navlink('pages/msd/renovar_servicio.php')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items" style="padding-bottom: 10px"><h1>Elije el plazo que mas te acomode</h1></div>
                <div class="planes clearfix">
                    <div class="plan">
                        <div class="detalleplan">
                            <div class="cont_detalle" onclick="navlink('pages/msd/renovar_servicio_detalle.php?pago=1')">
                                <h1>Plan<br/>Mensual</h1>
                                <h2>No hay ahorro</h2>
                                <h3>$50.000 + iva</h3>
                            </div>
                        </div>
                    </div>
                    <div class="plan">
                        <div class="detalleplan">
                            <div class="cont_detalle" onclick="navlink('pages/msd/renovar_servicio_detalle.php?pago=2')">
                                <h1>Plan<br/>Semestral</h1>
                                <h2>Ahorra 1 mes al año</h2>
                                <h3>$275.000 + iva</h3>
                            </div>
                        </div>
                    </div>
                    <div class="plan">
                        <div class="detalleplan">
                            <div class="cont_detalle" onclick="navlink('pages/msd/renovar_servicio_detalle.php?pago=3')">
                                <h1>Plan<br/>Anual</h1>
                                <h2>Ahorra 2 meses al año</h2>
                                <h3>$500.000 + iva</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>