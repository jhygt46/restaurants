<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

/* CONFIG PAGE */
$titulo = "Configuracion ".$_GET["nombre"];
$sub_titulo1 = "Modificar Configuracion";
$accion = "configurar_categoria";
/* CONFIG PAGE */

$id_mot = 0;
$sub_titulo = $sub_titulo1;
$id_loc = $_GET["id_loc"];
$loc_nombre = $_GET["nombre"];
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_mot"]) && is_numeric($_GET["id_mot"]) && $_GET["id_mot"] != 0){
    
    $id_mot = $_GET["id_mot"];
    $that = $core->get_repartidor($id_mot);
    $uid = $that['uid'];

}

?>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/crear_repartidor.php?id_loc=<?php echo $id_loc; ?>&nombre=<?php echo $loc_nombre; ?>')"></li>
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
                    <label class="clearfix">
                        <span><p>GPS App:</p></span>
                        <div class="btn_info"><div class="info">GPS Logger</div></div>
                    </label>
                    <label class="clearfix">
                        <span><p>URL App:</p></span>
                        <div class="btn_info"><div class="info">https://www.izusushi.cl/cambiar_posicion?lat=%LAT&lng=%LON&id=<?php echo $id_mot; ?>&uid=<?php echo $uid; ?></div></div>
                    </label>
                    <label class="clearfix">
                        <span><p>App Paso 1:</p></span>
                        <div class="btn_info"><div class="info">Abrir Aplicacion</div><img src="../images/app_paso_01.jpg" /></div>
                    </label>
                    <label class="clearfix">
                        <span><p>App Paso 2:</p></span>
                        <div class="btn_info"><div class="info">Abrir Aplicacion</div><img src="../images/app_paso_02.jpg" /></div>
                    </label>
                    <label class="clearfix">
                        <span><p>App Paso 3:</p></span>
                        <div class="btn_info"><div class="info">Abrir Aplicacion</div><img src="../images/app_paso_03.jpg" /></div>
                    </label>
                    <label class="clearfix">
                        <span><p>App Paso 4:</p></span>
                        <div class="btn_info"><div class="info">Abrir Aplicacion</div><img src="../images/app_paso_04.jpg" /></div>
                    </label>
                    <label class="clearfix">
                        <span><p>App Paso 5:</p></span>
                        <div class="btn_info"><div class="info">Abrir Aplicacion</div><img src="../images/app_paso_05.jpg" /></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>