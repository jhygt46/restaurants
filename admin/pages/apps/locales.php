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
$titulo = "Locales";
$titulo_list = "Mis Locales";
$sub_titulo1 = "Ingresar Local";
$sub_titulo2 = "Modificar Local";
$accion = "crear_locales";

$eliminaraccion = "eliminar_locales";
$id_list = "id_loc";
$eliminarobjeto = "Local";
$page_mod = "pages/apps/locales.php";
/* CONFIG PAGE */



$giro = $fireapp->get_giro();
$titulo = $titulo." de ".$giro['nombre'];
$list = $fireapp->get_locales();
$catalogos = $fireapp->get_catalogos();

$id_loc = 0;
$sub_titulo = $sub_titulo1;
$lat = -33.428843;
$lng = -70.620346;

if(isset($_GET["id_loc"]) && is_numeric($_GET["id_loc"]) && $_GET["id_loc"] != 0){

    $id_loc = $_GET["id_loc"];
    $that = $fireapp->get_local($id_loc);
    $sub_titulo = $sub_titulo2;
    $lat = $that["lat"];
    $lng = $that["lng"];
    
    echo "<pre>";
    print_r($that);
    echo "</pre>";

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
    
    function initMap(variable, lat, lng, zoom = 8) {
        return new google.maps.Map(document.getElementById(variable), { center: { lat: lat, lng: lng }, zoom: zoom } );
    }
    
}
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
                    <input id="id_loc" type="hidden" value="<?php echo $id_loc; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <input id="lat" type="hidden" value="<?php echo $that['lat']; ?>" />
                    <input id="lng" type="hidden" value="<?php echo $that['lng']; ?>" />
                    <label>
                        <span>Nombre:</span>
                        <input id="nombre" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <span>Correo:</span>
                        <input id="correo" type="text" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <span>Direccion:</span>
                        <input id="direccion" type="text" value="<?php echo $that['direccion']; ?>" require="" placeholder="" />
                    </label>
                    <div style="margin-left: 16%; margin-right: 9%; margin-top: 10px; width: 75%">
                        <div id="input_gmap" style="height: 260px"></div>
                    </div>
                    <label>
                        <span>Catalogo:</span>
                        <select id="id_cat">
                            <option value="0">Seleccionar</option>
                            <?php for($i=0; $i<count($catalogos); $i++){ ?>
                                <option value="<?php echo $catalogos[$i]["id_cat"]; ?>" <?php if($catalogos[$i]["id_cat"] == $that['id_cat']){ echo "selected"; } ?> ><?php echo $catalogos[$i]["nombre"]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
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
                    $code = $list[$i]['code'];
                ?>
                
                <li class="user">
                    <ul class="clearfix">
                        <li class="nombre"><?php echo $nombre; ?></li>
                        <a title="Eliminar" class="icn borrar" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $id_n; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a title="Modificar" class="icn modificar" onclick="navlink('<?php echo $page_mod; ?>?id_loc=<?php echo $id_n; ?>')"></a>
                        <a title="Zona de Despacho" class="icn despacho" onclick="navlink('pages/apps/zonas_locales.php?id_loc=<?php echo $id_n; ?>')"></a>
                        <a title="Punto de Venta" class="icn pventa" href="../locales.php?code=<?php echo $code; ?>" target="_blank"></a>
                    </ul>
                </li>
                
                <?php } ?>
                
            </ul>
            
        </div>
    </div>
</div>
<br />
<br />