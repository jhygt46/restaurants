$(document).ready(function(){
    socket_init();
    listar_pedidos(pedidos);
    modificar_horas();
    gmap_input();
});

var aud1 = new Audio('/audios/Ba-dum-tss.mp3');
var aud2 = new Audio('/audios/Aww.mp3');

var seleccionado = 0;
var categoria = 0;
var catalogo = 0;
var crear_nuevo = 0;
var time = new Date().getTime();
var markers = [];
var map_socket, socket;

function listar_pedidos(n){
    
    if(n !== undefined){
        localStorage.setItem("pedidos", JSON.stringify(n));
        var pedidos = n;
    }else{
        var pedidos = JSON.parse(localStorage.getItem("pedidos")) || false;
    }

    $('.lista_pedidos').html('');
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            if(pedidos[i].eliminado == 0 && pedidos[i].ocultar == 0){
                $('.lista_pedidos').append(html_home_pedidos(pedidos[i], i));
            }
        }
    }

}
function socket_init(){
    
    var code = localStorage.getItem("code");
    if(code != ""){

        socket = io.connect('https://www.izusushi.cl', { 'secure': true });
        socket.on('local-'+code, function(id_ped) {
            agregar_pedido(id_ped);
        });
        socket.on('enviar-chat-'+code, function(info){
            var id_ped = info.id_ped;
            var mensaje = info.mensaje;
            for(var i=0, ilen=pedidos.length; i<ilen; i++){
                if(pedidos[i].id_ped == id_ped){
                    if(!pedidos[i].hasOwnProperty('mensajes')){
                        pedidos[i].mensajes = [];
                        pedidos[i].mensajes_cont = 0;
                    }
                    if($('.p7').is(':visible') && id_ped == $('.p7').attr('id')){
                        pedidos[i].mensajes_cont = 0;
                    }else{
                        sound(aud2);
                        pedidos[i].mensajes_cont = pedidos[i].mensajes_cont + 1;
                    }
                    pedidos[i].mensajes.push({ tipo: 0, mensaje: mensaje });
                    listar_pedidos(pedidos);
                }
            }
            if($('.p7').is(':visible') && id_ped == $('.p7').attr('id')){
                $('.p7 .cont_conversacion').append("<div class='chat_2'><div class='nom'>"+pedidos[seleccionado].nombre+": </div><div class='msg'>"+mensaje+"</div></div>");
                $(".conversacion").scrollTop($(".cont_conversacion").outerHeight());
            }
        });
        socket.on('map-'+code, function(moto) {
            var info = JSON.parse(moto.info);
            for(var i=0, ilen=motos.length; i<ilen; i++){
                if(motos[i].id_mot == info.id_mot){
                    markers[i].setMap(map_socket);
                    markers[i].setPosition(new google.maps.LatLng(info.lat,info.lng));
                    motos[i].fecha = new Date().getTime();
                }
                var tiempo = motos[i].fecha - new Date().getTime();
                if(tiempo > 600000){
                    markers[i].setMap(null);
                }
            }
        });
        socket.on('connect', function() {
            $('.alert_socket').hide();
        });
        socket.on('disconnect', function() {
            $('.alert_socket').show();
        });
        //localStorage.setItem('code', '');

    }else{
        $(location).attr('href','/admin');
    }
    
}
function modificar_horas(){
    
    var pedidos = get_pedidos_false();
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){

            var fecha_ahora = Math.round(new Date().getTime()/1000);
            var time = (pedidos[i].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
            var diff = Math.round((pedidos[i].fecha + time - fecha_ahora)/60);
            if(diff < 0){ diff = 0; }
            $('.lista_pedidos').find('.pedido').eq(i).find('.t_tiempo').find('.t_nombre').html(diff);

        }
    }
    setTimeout(modificar_horas, 6000);
    
}
function get_pedidos_false(){
    return JSON.parse(localStorage.getItem("pedidos")) || false;
}
function gmap_input(){
    
    var input = document.getElementById('direccion');
    var searchBox = new google.maps.places.SearchBox(input);
    
    searchBox.addListener('places_changed', function(){
        var places = searchBox.getPlaces();
        if(places.length == 0){
            return;
        }
        if(places.length == 1){
            
            $('#lat').val(places[0].geometry.location.lat());
            $('#lng').val(places[0].geometry.location.lng());
            $('#direccion').val(places[0].formatted_address);
            $('#id_pdir').val(0);

            for(var i=0; i<places[0].address_components.length; i++){
                if(places[0].address_components[i].types[0] == "street_number"){
                    $('#num').val(places[0].address_components[i].long_name);
                }
                if(places[0].address_components[i].types[0] == "route"){
                    $('#calle').val(places[0].address_components[i].long_name);
                }
                if(places[0].address_components[i].types[0] == "locality"){
                    $('#comuna').val(places[0].address_components[i].long_name);
                }
            }
            var send = { accion: 'despacho_domicilio', lat: places[0].geometry.location.lat(), lng: places[0].geometry.location.lng(), id: id };
            
            $.ajax({
                url: "/ajax/index.php",
                type: "POST",
                data: send,
                success: function(datas){
                    var data = JSON.parse(datas);                   
                    if(data.op == 1){
                        $('#costo').val(data.precio);
                    }else{
                        alert("Su domicilio no se encuentra en la zona de reparto, disculpe las molestias");
                        $('#costo').val(-1);
                    }
                }, error: function(e){
                    alert("Se produjo un error: intente mas tarde");
                    $('#costo').val(-1);
                }
            });
            
        }
    });
}