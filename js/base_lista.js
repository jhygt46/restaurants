// CONFIRMAR PEDIDO //

function show_retiro(){
    $('.cont_direccion .direccion_opciones').hide();
    $('.cont_direccion .direccion_op1').show();
}
function show_despacho(){
    if(map_init == 0){
        initMap();
    }
    $('.cont_direccion .direccion_opciones').hide();
    $('.cont_direccion .direccion_op2').show();
}
function map_local(id){    
    $('#lmap-'+id).toggle();
    if(maps.indexOf(id) == -1){
        init_map_local(id);
        maps.push(id);
    }
}
function nuevo_pedido(){
    set_pedido(null);
    borrar_carro();
    hide_modal();
    location.reload();
}
function paso_2(){
    var pedido = get_pedido();
    if(pedido.despacho !== null){
        $('.acc_paso2').show();
        if(pedido.despacho == 0){
            $('.direccion_opciones').find('.dir_op').eq(0).addClass('dir_op_select');
            $('.direccion_opciones').find('.dir_op').eq(0).find('.stitle').html(pedido.local_nombre);
        }else{
            $('.direccion_opciones').find('.dir_op').eq(0).removeClass('dir_op_select');
            $('.direccion_opciones').find('.dir_op').eq(0).find('.stitle').html('Sin Costo');
        }
        if(pedido.despacho == 1){
            $('.direccion_opciones').find('.dir_op').eq(1).addClass('dir_op_select');
            $('.direccion_opciones').find('.dir_op').eq(1).find('.stitle').html(pedido.direccion);
        }else{
            $('.direccion_opciones').find('.dir_op').eq(1).removeClass('dir_op_select');
            $('.direccion_opciones').find('.dir_op').eq(1).find('.stitle').html('Desde $1.000');
        }
    }else{
        $('.acc_paso2').hide();
        $('.direccion_opciones').find('.dir_op').eq(0).removeClass('dir_op_select');
        $('.direccion_opciones').find('.dir_op').eq(0).find('.stitle').html('Sin Costo');
        $('.direccion_opciones').find('.dir_op').eq(1).removeClass('dir_op_select');
        $('.direccion_opciones').find('.dir_op').eq(1).find('.stitle').html('Desde $1.000');
    }
    $('.paso_01').hide();
    $('.paso_02').show();
}
function paso_3(){
    
    var pedido = get_pedido();
    if(pedido.despacho !== null){
        var total = parseInt(pedido.total) + parseInt(pedido.costo);

        if(pedido.despacho == 0){
            $('.fs_dire').hide();
        }
        if(pedido.despacho == 1){
            $('.fs_dire').show();
            $('.render_dir').html(pedido.calle+" "+pedido.num);
        }

        $('.fin_pedido .fin_dll_price').html(formatNumber.new(parseInt(pedido.total), "$"));
        $('.fin_despacho .fin_dll_price').html(formatNumber.new(parseInt(pedido.costo), "$"));
        $('.fin_total .fin_dll_price').html(formatNumber.new(total, "$"));

        $('.paso_02').hide();
        $('.paso_03').show();
    }else{
        
    }
    
}
function paso_4(){
    
    document.getElementById("enviar_cotizacion").disabled = true;
    
    var pedido = get_pedido();
    pedido.nombre = $('.pedido_nombre').val();
    pedido.telefono = $('.pedido_telefono').val();
    pedido.depto = $('.pedido_depto').val();
    pedido.gengibre = $('#pedido_gengibre').val();
    pedido.wasabi = $('#pedido_wasabi').val();
    pedido.embarazadas = $('#pedido_embarazadas').val();
    pedido.palitos = $('#pedido_palitos').val();
    
    var send = { accion: 'enviar_pedido', pedido: JSON.stringify(pedido), carro: JSON.stringify(get_carro()), promos: JSON.stringify(get_promos()) };
    $.ajax({
        url: "/ajax/index.php",
        type: "POST",
        data: send,
        success: function(info){
            
            var data = JSON.parse(info);

            if(data.op == 1){
                
                pedido.id_ped = data.id_ped;
                pedido.pedido_code = data.pedido_code;
                pedido.position_lat = data.position_lat;
                pedido.position_lng = data.position_lng;
                
                set_pedido(pedido);
                open_socket(pedido);
                
                $('.paso_03').hide();
                $('.paso_04').show();
                document.getElementById("enviar_cotizacion").disabled = false;
                
            }else{
                document.getElementById("enviar_cotizacion").disabled = false;
            }
        }, error: function(e){
            console.log(e);
        }
    });
    
}

function select_local(id, nombre){
    
    var pedido = get_pedido();
    pedido.despacho = 0;
    pedido.id_loc = id;
    pedido.local_nombre = nombre;
    pedido.costo = 0;
    set_pedido(pedido);
    paso_3();

}

var marker_pos;
function open_socket(pedido){
    
    var pedido = get_pedido();
    console.log(pedido);
    
    var myLatlng = new google.maps.LatLng(pedido.position_lat, pedido.position_lng);
    var mapOptions = {
        zoom: 18,
        center: myLatlng,
        mapTypeId: 'roadmap',
        disableDefaultUI: true
    }
    var map = new google.maps.Map(document.getElementById("mapa_posicion"), mapOptions);
    marker_pos = new google.maps.Marker({
        position: myLatlng,
        map: map
    });

    var estados = ['Enviado', 'Recepcionado', 'Preparando', 'Empaque', 'Despacho'];
    var socket = io.connect('http://35.196.220.197:80', { 'forceNew': true });
    
    socket.on('pedido-'+pedido.pedido_code, function(data){
        $('.info_modal_pedido .estado').html("Estado: "+estados[data.estado % estados.length]); 
    });
    socket.on('pedido-pos-'+pedido.pedido_code, function(data) {
        marker_pos.setPosition( new google.maps.LatLng( data.lat, data.lng ) );
    });
    
}

function initMap(){
    
    map_init = 1;
    map = new google.maps.Map(document.getElementById('map_direccion'), {
        center: {lat: -33.428066, lng: -70.616695},
        zoom: 13,
        mapTypeId: 'roadmap',
        disableDefaultUI: true
    });
    
    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });
    
    var markers = [];
    searchBox.addListener('places_changed', function(){
        
        var places = searchBox.getPlaces();
        if(places.length == 0){
            return;
        }
        if(places.length == 1){
            
            var num = 0;
            var calle = "";
            var comuna = "";
            
            for(var i=0; i<places[0].address_components.length; i++){
                if(places[0].address_components[i].types[0] == "street_number"){
                    num = places[0].address_components[i].long_name;
                }
                if(places[0].address_components[i].types[0] == "route"){
                    calle = places[0].address_components[i].long_name;
                }
                if(places[0].address_components[i].types[0] == "locality"){
                    comuna = places[0].address_components[i].long_name;
                }
            }
            
            if(num != 0){
                
                var send = {accion: 'despacho_domicilio', lat: places[0].geometry.location.lat(), lng: places[0].geometry.location.lng()};
                $.ajax({
                    url: "/ajax/index.php",
                    type: "POST",
                    data: send,
                    success: function(datas){
                        
                        console.log("DESPACHO DOMICILIO");
                        console.log(datas);
                        
                        var data = JSON.parse(datas);

                        if(data.op == 1){

                            var pedido = get_pedido();
                            pedido.id_loc = data.id_loc;
                            pedido.costo = data.precio;
                            pedido.despacho = 1;
                            pedido.lat = places[0].geometry.location.lat();
                            pedido.lng = places[0].geometry.location.lng();
                            pedido.direccion = places[0].formatted_address;
                            pedido.num = num;
                            pedido.calle = calle;
                            pedido.comuna = comuna;
                            set_pedido(pedido);
                            paso_3();
                            
                        }else{
                            alert("Su domicilio no se encuentra en la zona de reparto, disculpe las molestias")
                        }
                        
                    }, error: function(e){
                        console.log(e);
                    }
                });
                
            }else{
                alert("DEBE INGRESAR DIRECCION EXACTA");
            }

        }
        // Clear out the old markers.
        markers.forEach(function(marker){
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place){
            if(!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if(place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            }else{
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });

}