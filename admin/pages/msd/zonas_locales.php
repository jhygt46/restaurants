<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$core = new Core();

/* CONFIG PAGE */
$titulo = "Tramos";
$titulo_list = "Mis Tramos";
$sub_titulo1 = "Ingresar Tramo";
$sub_titulo2 = "Modificar Tramo";
$accion = "crear_locales_tramos";

$eliminaraccion = "eliminar_tramos";
$id_list = "id_lot";
$eliminarobjeto = "Tramo";
$page_mod = "pages/msd/zonas_locales.php";
/* CONFIG PAGE */

$id_loc = 0;
$sub_titulo = $sub_titulo1;
$class = ($_POST['w'] < 700) ? 'resp' : 'normal' ;

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){

    $id_loc = $_GET["id_loc"];
    $list = $core->get_local_tramos($id_loc);
    $id_lot = 0;
    
    if(isset($_GET["id_lot"]) && is_numeric($_GET["id_lot"]) && $_GET["id_lot"] != 0){
        
        $sub_titulo = $sub_titulo2;
        $id_lot = $_GET["id_lot"];
        $that = $core->get_local_tramo($id_lot);
        
    }

}
/*
echo "<pre>";
print_r($core->get_info_despacho(-33.43457716115334, -70.601704));
echo "</pre>";
*/
?>
<script>
    labelIndex = 0;
    iniciar_mapa();
    testmarker(-33.450599, -70.6124045);
    renderMarkers_mod(<?php echo $that['poligono']; ?>);
</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php?id_gir=<?php echo $_SESSION['user']['id_gir']; ?>')"></li>
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
                    <textarea id="posiciones" style="display:none"><?php echo $that['poligono']; ?></textarea>
                    <input id="id_lot" type="hidden" value="<?php echo $id_lot; ?>" />
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" type="text" class="inputs" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Precio:</p></span>
                        <input id="precio" type="text" class="inputs" value="<?php echo $that['precio']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Mapa:</p></span>
                        <div id="map"></div>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo"><h1><?php echo $titulo_list; ?></h1></div>
                <ul class="opts clearfix">
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id_loc; ?>/<?php echo $id_n; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_lot=<?php echo $id_n; ?>&id_loc=<?php echo $id_loc; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>