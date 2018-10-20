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
$titulo = "Tramos";
$titulo_list = "Mis Tramos";
$sub_titulo1 = "Ingresar Tramo";
$sub_titulo2 = "Modificar Tramo";
$accion = "crear_locales_tramos";

$eliminaraccion = "eliminar_tramos";
$id_list = "id_lot";
$eliminarobjeto = "Tramo";
$page_mod = "pages/apps/zonas_locales.php";
/* CONFIG PAGE */

$id_loc = 0;
$sub_titulo = $sub_titulo1;
if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){

    $id_loc = $_GET["id_loc"];
    $list = $fireapp->get_local_tramos($id_loc);
    $id_lot = 0;
    
    if(isset($_GET["id_lot"]) && is_numeric($_GET["id_lot"]) && $_GET["id_lot"] != 0){
        
        $sub_titulo = $sub_titulo2;
        $id_lot = $_GET["id_lot"];
        $that = $fireapp->get_local_tramo($id_lot);
        
    }

}

?>
<script>
    iniciar_mapa();
    renderMarkers_mod(<?php echo $that['poligono']; ?>);
</script>
<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>
<div class="info">
    <div class="fc" id="info-0">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $sub_titulo; ?></div>
        <div class="message"></div>
        <div class="sucont">

            <form action="" method="post" class="basic-grey">
                <fieldset>
                    <textarea id="posiciones" style="display:none"><?php echo $that['poligono']; ?></textarea>
                    <input id="id_lot" type="hidden" value="<?php echo $id_lot; ?>" />
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <span>Precio:</span>
                        <input id="precio" type="text" value="<?php echo $that['precio']; ?>" require="" placeholder="" />
                    </label>
                    <div style="margin-left: 16%; margin-right: 9%; margin-top: 10px; width: 75%">
                        <div id="map" style="height: 460px; background: #f00"></div>
                    </div>
                    <label style='margin-top:20px'>
                        <span>&nbsp;</span>
                        <a id='button' onclick="form()">Enviar</a>
                    </label>
                </fieldset>
            </form>
            
        </div>
    </div>
</div>

<div class="info">
    <div class="fc" id="info-0">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $titulo_list; ?></div>
        <div class="message"></div>
        <div class="sucont">
            
            <ul class='listUser'>
                
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                ?>
                
                <li class="user">
                    <ul class="clearfix">
                        <li class="nombre"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id_loc; ?>/<?php echo $id_n; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id_lot=<?php echo $id_n; ?>&id_loc=<?php echo $id_loc; ?>')"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />