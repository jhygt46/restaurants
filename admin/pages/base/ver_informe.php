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

$id_gir = 0;
if(isset($_GET["id_gir"]) && is_numeric($_GET["id_gir"]) && $_GET["id_gir"] != 0){
    
    $id_gir = $_GET["id_gir"];
    $fireapp->is_giro($id_gir);
    
}

if($_SESSION['user']['info']['id_user'] == 1){
    echo "<div class='panel_admin'>";
    echo "<div class='data_info'>pages/base/ver_informe.php</div>";
    echo "</div>";
}

?>
<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="reload" onclick="refresh()"></li>
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
<div class="info" onclick="navlink('pages/apps/locales.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Locales</div>
        <div class="name2">Ingresa la informacion de tus locales</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/apps/configurar_giro.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Configuracion</div>
        <div class="name2">Configura tu sitio web</div>
        <div class="go_app"></div>
    </div>
</div>
<div class="info" onclick="navlink('pages/base/usuarios.php')">
    <div class="fc" id="info-0" style="height: 54px">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name">Usuarios</div>
        <div class="name2">Agrega o elimina usuarios</div>
        <div class="go_app"></div>
    </div>
</div>
