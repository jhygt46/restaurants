<?php
session_start();
?>
<div class="title">
    <h1>Configuracion </h1>
    <ul class="clearfix">
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>

<div class="info" onclick="navlink('pages/apps/configurar_giro_conf.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Configuracion</div>
        <div class="name2">Titulo, Estilos, Colores, Fuentes</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/configurar_giro_paginas.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Paginas</div>
        <div class="name2">Ingresa paginas en tu menu</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/configurar_giro_footer.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Footer</div>
        <div class="name2">Selecciona tu footer</div>
        <div class="go_app"></div>
    </div>
</div>
