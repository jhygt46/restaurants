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
<script>

    var map, infoWindow;
    function initMap(){

        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 15
        });
        infoWindow = new google.maps.InfoWindow;

        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                infoWindow.open(map);
                map.setCenter(pos);
            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        }else{
          handleLocationError(false, infoWindow, map.getCenter());
        }
    }
    function handleLocationError(browserHasGeolocation, infoWindow, pos){
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }
    initMap();

</script>
<div class="pagina">
    <div class="title">
        <h1>Mapa de Restaurants</h1>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items" style="padding-bottom: 10px"><h1>Posicion de restaurantes existantes</h1></div>
                <div id="map" style="width: 100%; height: 500px; background: #f00"></div>
            </div>
        </div>
    </div>
</div>