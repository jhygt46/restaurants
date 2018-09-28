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
$titulo_list = "Aplicaciones";
/* CONFIG PAGE */

$id_cat = 0;
if(isset($_GET["id_cat"]) && is_numeric($_GET["id_cat"]) && $_GET["id_cat"] != 0){
    
    $id_cat = $_GET["id_cat"];
    $fireapp->is_catalogo($id_cat);
    $info = $fireapp->get_catalogo();
    $titulo = $info['nombre'];
    
}



?>
<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>

<div class="info" onclick="navlink('pages/apps/categorias.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Carta</div>
        <div class="name2">Todos los productos agrupado por categorias</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/promociones.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Promociones</div>
        <div class="name2">Productos Agrupados con precios baratos</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/ingredientes.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Ingredientes</div>
        <div class="name2">Activa los ingredientes</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/preguntas.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Preguntas</div>
        <div class="name2">Para categorias y Productos</div>
        <div class="go_app"></div>
    </div>
</div>
