var markers = [];
var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var labelIndex = 0;
var map;
var bermudaTriangle = null;

function iniciar_mapa() {
    
    markers = [];
    var bangalore = { lat: -33.405412, lng: -70.653332 };
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: bangalore
    });
    // This event listener calls addMarker() when the map is clicked.
    google.maps.event.addListener(map, 'click', function(event){
        addMarker(event.latLng, map);
        renderMarkers();
    });
    
}
function testmarker(lat, lng){
    var marker = new google.maps.Marker({
        position: {lat: lat, lng: lng},
        label: 'BUE',
        map: map
    });
}
function addMarker(location, map) {
    
    var marker = new google.maps.Marker({
        position: location,
        draggable: true,
        label: labels[labelIndex++ % labels.length],
        map: map
    });
    marker.addListener('click', function() {
        // DELETE MARKER OF MARKERS
        markers.forEach(function(item, index, object) {
            if (item.label === marker.label) {
                object.splice(index, 1);
            }
        });
        marker.setMap(null);
        bermudaTriangle.setMap(null);
        renderMarkers();
    });
    
    marker.addListener('dragend', function() {
        renderMarkers();
    });
    
    markers.push(marker);
    
}



function renderMarkers(){
    
    var Coords = [];
    markers.forEach(function(item, index, object) {
        Coords.push({lat: item.position.lat(), lng: item.position.lng()});
    });
    if(bermudaTriangle !== null){ setnull(); }
    bermudaTriangle = new google.maps.Polygon({
        paths: Coords,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: '#FF0000',
        fillOpacity: 0.35
    });
    bermudaTriangle.setMap(map);
    $('#posiciones').html(JSON.stringify(Coords));

}
function renderMarkers_mod(posiciones){
    if(posiciones !== undefined){
        posiciones.forEach(function(item) {
            addMarker({lat: item.lat, lng: item.lng}, map);
        });
        renderMarkers();
    }
}
function setnull(){
    bermudaTriangle.setMap(null);
}