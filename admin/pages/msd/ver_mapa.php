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
        <h1>Mapa de Restaurants</h1>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items" style="padding-bottom: 10px"><h1>Posicion de restaurantes existantes</h1></div>
                <div id="mapa" style="width: 100%; height: 500px; background: #f00"></div>
            </div>
        </div>
    </div>
</div>