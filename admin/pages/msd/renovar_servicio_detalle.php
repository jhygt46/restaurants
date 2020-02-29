<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();
$monto = $core->get_monto();
$iva = 1.19;

if($_GET["pago"] == 1){
    $monto = $monto * $iva;
}
if($_GET["pago"] == 2){
    $monto = $monto * 5.5 * $iva;
}
if($_GET["pago"] == 3){
    $monto = $monto * 10 * $iva;
}

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
                <div class="titulo_items"><h1>Datos para transferencia electronica</h1></div>
                <div class="detalle_transferencia">
                    <h1>Banco</h1>
                    <h2>Santader Santiago</h2>
                    <h1>Rut</h1>
                    <h2>15.935.774-0</h2>
                    <h1>Tipo</h1>
                    <h2>Cuenta corriente</h2>
                    <h1>Correo</h1>
                    <h2>misitiodelivery@gmail.com</h2>
                    <h1>Monto</h1>
                    <h2>$<?php echo number_format($monto, 0, '', '.'); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>