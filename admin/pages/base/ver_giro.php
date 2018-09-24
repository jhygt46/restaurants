<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();


$fireapp->is_giro($_GET["id_gir"]);

/* CONFIG PAGE */
$titulo = $_GET["nombre"];
$titulo_list = "Aplicaciones";
/* CONFIG PAGE */

$id_gir = 0;
if(isset($_GET["id_gir"]) && is_numeric($_GET["id_gir"]) && $_GET["id_gir"] != 0){
    
    $id_gir = $_GET["id_gir"];
    $paso = $fireapp->paso_giro($id_gir);
    
}

?>

<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="reload" onclick="refresh(<?php echo $id_gir; ?>)"></li>
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>

<div class="info" onclick="navlink('<?php echo $paso; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Catalogo de Productos</div>
        <div class="name2">Ingresa todas las categorias y productos</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/locales.php?id_gir=<?php echo $id_gir; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Locales</div>
        <div class="name2">Ingresa la informacion de tus locales</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/configurar_giro.php?id=<?php echo $id_gir; ?>')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Configuracion</div>
        <div class="name2">Configura tu sitio web</div>
        <div class="go_app"></div>
    </div>
</div>
