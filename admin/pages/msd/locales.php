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
$titulo = "Locales";
$titulo_list = "Mis Locales";
$sub_titulo1 = "Ingresar Local";
$sub_titulo2 = "Modificar Local";
$accion = "crear_locales";

$eliminaraccion = "eliminar_locales";
$id_list = "id_loc";
$eliminarobjeto = "Local";
$page_mod = "pages/msd/locales.php";
/* CONFIG PAGE */

$giro = $core->get_giro();
$titulo = $titulo." de ".$giro['nombre'];
$list = $core->get_locales();
$catalogos = $core->get_catalogos();
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

$id_loc = 0;
$sub_titulo = $sub_titulo1;
$lat = -33.428843;
$lng = -70.620346;

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){

    $id_loc = $_GET["id_loc"];
    $that = $core->get_local($id_loc);
    $sub_titulo = $sub_titulo2;
    $lat = $that["lat"];
    $lng = $that["lng"];

}

?>
<script>
    
var map;
var markers = Array();
$(document).ready(function(){

    map = initMap('input_gmap', <?php echo $lat; ?>, <?php echo $lng; ?>, 17);
    <?php if(isset($that)){ ?> addmarker(map, <?php echo $lat; ?>, <?php echo $lng; ?>); <?php } ?>
    crear_llamado(map);

});
function addmarker(map, lat, lng){
    
    var myLatLng = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Ubicacion'
    });

}
function initMap(variable, lat, lng, zoom = 8) {
    return new google.maps.Map(document.getElementById(variable), { center: { lat: lat, lng: lng }, zoom: zoom } );
}
function crear_llamado(map){
        
    var searchBox = new google.maps.places.SearchBox(document.getElementById("direccion"));
    searchBox.addListener('places_changed', function(){
        var places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        $("#address").val(places[0].formatted_address);
        $("#lat").val(places[0].geometry.location.lat());
        $("#lng").val(places[0].geometry.location.lng());

        markers.forEach(function(marker){
            marker.setMap(null);
        });
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place){
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));
            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            }else{
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
    map.addListener('bounds_changed', function(){
        searchBox.setBounds(map.getBounds()); 
    });
    
    
    
}
</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php')"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <input id="lat" type="hidden" value="<?php echo $lat; ?>" />
                    <input id="lng" type="hidden" value="<?php echo $lng; ?>" />
                    <input id="address" type="hidden" value="<?php echo $that['direccion']; ?>" />
                    <?php if(count($catalogos)==1){ ?><input id="id_cat" type="hidden" value="<?php echo $catalogos[0]["id_cat"]; ?>" /><?php } ?>
                    <label class="clearfix">
                        <span><p>Nombre del Local:</p></span>
                        <input id="nombre" type="text" class="inputs" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Correo del Local:</p></span>
                        <input id="correo" type="text" class="inputs" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Direccion del Local:</p></span>
                        <input id="direccion" type="text" class="inputs" value="<?php echo $that['direccion']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Mapa:</p></span>
                        <div class="map" id="input_gmap"></div>
                    </label>
                    <?php if(count($catalogos)>1){ ?>
                    <label class="clearfix">
                        <span><p>Catalogo:</p></span>
                        <select id="id_cat">
                            <option value="0">Seleccionar</option>
                            <?php for($i=0; $i<count($catalogos); $i++){ ?>
                                <option value="<?php echo $catalogos[$i]["id_cat"]; ?>" <?php if($catalogos[$i]["id_cat"] == $that['id_cat']){ echo "selected"; } ?> ><?php echo $catalogos[$i]["nombre"]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <?php } ?>
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
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic7" onclick="navlink('pages/msd/configurar_local.php?id_loc=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_loc=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic4" onclick="navlink('pages/msd/zonas_locales.php?id_loc=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic13" onclick="navlink('pages/msd/crear_repartidor.php?id_loc=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic14" onclick="navlink('pages/msd/crear_horario.php?id_loc=<?php echo $id; ?>&nombre=<?php echo $nombre; ?>')"></a>
                        <a class="icono ic16" onclick="navlink('pages/msd/usuarios_giro.php?id_loc=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>