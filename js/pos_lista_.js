$(document).ready(function(){
    socket_init();
    listar_pedidos(pedidos);
    modificar_horas();
    gmap_input();
    resize();

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
var version = 0;
var global_telefono = "";
var pedido_height = 0;

function resize(){

    var w = window.innerWidth;
    if(w <= 768){
        // VERSION MOBILE
        version = 0;
    }else{
        // VERSION WEB
        version = 1;
    }

}
function get_pedidos(){
    return JSON.parse(localStorage.getItem("pedidos"));
}
function listar_pedidos(pedidos){
    
    if(pedidos !== undefined){
        localStorage.setItem("pedidos", JSON.stringify(pedidos));
        var aux_ped = pedidos;
    }else{
        var aux_ped = get_pedidos();
    }
    $('.cont_lista').html('');
    if(aux_ped.length){
        for(var i=0, ilen=aux_ped.length; i<ilen; i++){
            if(aux_ped[i].eliminado == 0 && aux_ped[i].ocultar == 0){
                $('.cont_lista').append(html_home_pedidos(i));
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

    }else{
        $(location).attr('href','/admin');
    }
    
}
function modificar_horas(){
    
    var pedidos = get_pedidos();
    var time = 0;
    var diff = 0;
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            if(pedidos[i].tipo == 1){
                time = (pedidos[i].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
                diff = Math.round((pedidos[i].fecha + (time*60) - Math.round(new Date().getTime()/1000))/60);
                if(diff < 0){ diff = 0; }
                $('.lista_pedidos').find('.pedido').eq(i).find('.t_tiempo').find('.t_nombre').html(diff);
            }
        }
    }
    setTimeout(modificar_horas, 60000);
    
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
            var send = { lat: places[0].geometry.location.lat(), lng: places[0].geometry.location.lng(), accion: 'despacho_domicilio' };
            
            $.ajax({
                url: "/ajax/",
                type: "POST",
                data: send,
                success: function(data){
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
function html_home_pedidos(index){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[index];

    var sub_total = get_precio_carro(pedido);

    if(pedido.despacho == 0){
        pedido.costo = 0;
    }

    var total = parseInt(sub_total) + parseInt(pedido.costo);

    if(sub_total != pedido.total){
        var total_dif = sub_total - parseInt(pedido.total);
        pedidos[index].alert = "Existe un diferencia de "+formatNumber.new(total_dif, "$");
    }

    if(seleccionado == index){
        if(pedido.alert == '' || pedido.alert === undefined){
            var Div = create_element_class('pedido pedido_h1 seleccionado');
        }else{
            var Div = create_element_class('pedido pedido_h2 seleccionado');
        }
    }else{
        if(pedido.alert == '' || pedido.alert === undefined){
            var Div = create_element_class('pedido pedido_h1');
        }else{
            var Div = create_element_class('pedido pedido_h2');
        }
    }
    
    Div.setAttribute('pos', index);
    if(pedido.despacho == 1){
        var p_estado = create_element_class_inner('p_estado', formatNumber.new(parseInt(pedido.costo), "$"));
    }
    if(pedido.despacho == 0){
        var p_estado = create_element_class_inner('p_estado', '');
    }

    var p_num = create_element_class_inner('p_num', "Pedido #"+pedido.num_ped);
    var p_nom = create_element_class_inner('p_nom', pedido.nombre);
    
    var p_precio = create_element_class_inner('p_precio', formatNumber.new(parseInt(total), "$"));
    var p_cont = create_element_class('p_cont');
    p_cont.onclick = function(){ set_pedido(index) };
    
    var btn_mod = create_element_class_inner('btn_mod material-icons', 'settings');
    btn_mod.onclick = function(){ ver_pedido(index) };
    
    if(pedidos[index].cambios == 0){
        var btn_open = create_element_class('btn_open');
    }else{
        var btn_open = create_element_class('btn_open select');
    }
    
    btn_open.onclick = function(){ guardar_pedido(index) };
    var flecha_01 = create_element_class('flecha_01');
    var flecha_02 = create_element_class('flecha_02');

    btn_open.appendChild(flecha_01);
    btn_open.appendChild(flecha_02);

    var btn_carro = create_element_class_inner('btn_carro material-icons', 'add_shopping_cart');
    btn_carro.onclick = function(){ ver_detalle_carro(index) };

    if(pedido.despacho == 1){
        var btn_map = create_element_class('btn_map');
        btn_map.onclick = function(){ abrir_map(pedido.lat, pedido.lng) };
        Div.appendChild(btn_map);
    }

    /*
    if(pedido.hasOwnProperty('mensajes_cont')){
        var btn_chat = create_element_class('btn_chat');
        btn_chat.onclick = function(){ abrir_chat(index) };
        Div.appendChild(btn_chat);
        if(pedido.mensajes_cont > 0){
            if(pedido.mensajes_cont === undefined){
                var m_count = 0;
            }else{
                var m_count = pedido.mensajes_cont;
            }
            var chat_num = create_element_class_inner('chat_num', m_count);
            Div.appendChild(chat_num);
        }
    }
    if(pedido.alert != '' && pedido.alert !== undefined){
        var p_alert = create_element_class_inner('p_alert', pedido.alert);
        Div.appendChild(p_alert);
    }
    */

    Div.appendChild(p_estado);
    Div.appendChild(p_cont);
    Div.appendChild(p_num);
    Div.appendChild(p_nom);
    Div.appendChild(p_precio);
    Div.appendChild(btn_mod);
    Div.appendChild(btn_open);
    Div.appendChild(btn_carro);
    
    if(pedido.tipo == 1){
        
        var estado = create_element_class('p_opciones');
        var anterior = create_element_class_inner('p_anterior material-icons', 'keyboard_arrow_left');
        anterior.onclick = function(){ cambiar_estado(index, -1) };
        var nombre = create_element_class_inner('p_nombre', estados[pedido.estado]);
        var siguiente = create_element_class_inner('p_siguiente material-icons', 'keyboard_arrow_right');
        siguiente.onclick = function(){ cambiar_estado(index, 1) };

        estado.appendChild(anterior);
        estado.appendChild(nombre);
        estado.appendChild(siguiente);

        
        var time = (pedidos[index].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
        var diff = Math.round((pedidos[index].fecha + (time*60) - Math.round(new Date().getTime()/1000))/60);
        if(diff < 0){ diff = 0; }
        
        var t_tiempo = create_element_class('t_tiempo');
        var t_anterior = create_element_class_inner('t_anterior material-icons', 'keyboard_arrow_left');
        t_anterior.onclick = function(){ cambiar_hora(index, -1) };
        var t_nombre = create_element_class_inner('t_nombre', diff);
        var t_siguiente = create_element_class_inner('t_siguiente material-icons', 'keyboard_arrow_right');
        t_siguiente.onclick = function(){ cambiar_hora(index, 1) };

        t_tiempo.appendChild(t_anterior);
        t_tiempo.appendChild(t_nombre);
        t_tiempo.appendChild(t_siguiente);
        
        Div.appendChild(estado);
        Div.appendChild(t_tiempo);
        
    }

    return Div;
    
}
function set_pedidos(pedidos){
    localStorage.setItem("pedidos", JSON.stringify(pedidos));
}
function enviar_cambio_de_hora(index){

    var pedidos = get_pedidos();
    if(pedidos[index].cambio_tiempo <= 1){

        var send = { accion: 'cambiar_estado', id_ped: pedidos[index].id_ped, fecha: pedidos[index].fecha, tipo: 1 };
        $.ajax({
            url: "/ajax/",
            type: "POST",
            data: send,
            success: function(){},
            error: function(){}
        });

    }
    pedidos[index].cambio_tiempo = pedidos[index].cambio_tiempo - 1;
    set_pedidos(pedidos);

}
function cambiar_hora(index, n){
    
    var pedidos = get_pedidos();
    pedidos[index].fecha = pedidos[index].fecha + n*60;
    pedidos[index].cambio_tiempo = pedidos[index].cambio_tiempo + 1;
    listar_pedidos(pedidos);
    setTimeout(function(){ enviar_cambio_de_hora(index) }, 10000);
    
}
function enviar_cambio_de_estado(index, aux){

    var pedidos = get_pedidos();
    if(pedidos[index].cambio_estado <= 1){
        
        var send = { accion: 'cambiar_estado', id_ped: pedidos[index].id_ped, estado: aux, tipo: 0 };
        $.ajax({
            url: "/ajax/",
            type: "POST",
            data: send,
            success: function(){},
            error: function(){}
        });

    }
    pedidos[index].cambio_estado = pedidos[index].cambio_estado - 1;
    set_pedidos(pedidos);

}
function cambiar_estado(index, n){

    var pedidos = get_pedidos();
    var aux = parseInt(pedidos[index].estado) + n;
    if(aux >= 0 && aux < estados.length){
        pedidos[index].estado = aux;
        pedidos[index].cambio_estado = pedidos[index].cambio_estado + 1;
        listar_pedidos(pedidos);
        setTimeout(function(){ enviar_cambio_de_estado(index, aux) }, 10000);
    }

}
function get_precio_carro(obj){

    var total = 0;

    if(Array.isArray(obj.carro) && obj.carro.length > 0){
        obj.carro.forEach(function(carro_item){
            if(carro_item.id_pro && carro_item.promo === undefined){
                var pro = get_producto(carro_item.id_pro);
                total = total + parseInt(pro.precio); 
            }
        });
    }

    if(Array.isArray(obj.promos) && obj.promos.length > 0){
        obj.promos.forEach(function(promo_item){
            var cat = get_categoria(promo_item.id_cae);
            total = total + parseInt(cat.precio);
        });
    }
    
    return total;

}
function get_pedido_blank(){
    return [pedido_obj()];
}
function pedido_obj(){
    return {
        id_ped: 0,
        num_ped: 0,
        pedido_code: '', 
        tipo: 0,
        alert: '',
        estado: 0,
        fecha: Math.round(new Date().getTime()/1000),
        cambio_tiempo: 0,
        cambio_estado: 0,
        despacho: 1,
        carro: [],  
        promos: [], 
        pre_wasabi: 0,
        pre_gengibre: 0,
        pre_palitos: 0,
        pre_soya: 0,
        pre_teriyaki: 0,
        id_mot: 0,
        id_puser: 0,
        id_pdir: 0,
        verificado: 0,
        nombre: '',
        telefono: '',
        direccion: '',
        calle: '',
        num: '',
        depto: '',
        lat: 0,
        lng: 0,
        costo: -1,
        total: 0,
        eliminado: 0,
        ocultar: 0,
        comentarios: ''
    };
}
function create_element_class(clase){
    var Div = document.createElement('div');
    Div.className = clase;
    return Div;
}
function create_element_class_inner(clase, value){
    var Div = document.createElement('div');
    Div.className = clase;
    Div.innerHTML = value;
    return Div;
}
function get_categoria(id_cae){
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].id_cae == id_cae){
            return categorias[i];
        }
    }
}
function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
        }
    }
}
function init_map(){
    
    var punto = { lat: parseFloat(local_lat), lng: parseFloat(local_lng) };
    map_socket = new google.maps.Map(document.getElementById('mapa_motos'), {
        center: punto,
        zoom: 17,
        mapTypeId: 'roadmap',
        disableDefaultUI: false
    });

    var icon_local = "https://maps.google.com/mapfiles/ms/icons/blue-dot.png";
    new google.maps.Marker({
        map: map_socket,
        title: 'Local',
        position: punto,
        icon: icon_local
    });

    var icon_moto = "https://maps.google.com/mapfiles/ms/icons/green-dot.png";
    for(var i=0, ilen=motos.length; i<ilen; i++){
        markers.push(new google.maps.Marker({
            map: null,
            title: motos[i].nombre,
            position: { lat: 0, lng: 0 },
            icon: icon_moto
        }))
        motos[i].fecha = new Date().getTime();
    }

}
function set_pedido(index){
    
    seleccionado = index;
    categorias_base(0);
    $('.lista_pedidos').find('.pedido').each(function(){
        if(index == $(this).attr('pos')){
            $(this).addClass('seleccionado');
        }else{
            $(this).removeClass('seleccionado');
        }
    });
    
}
function pop_up(n){

    $('.pop_up').show();
    $('.pop').hide();
    $('.'+n).show();

}
function open_categoria(id){

    if(cats_or_prods(id)){
        categorias_base(id);
    }else{
        open_productos(id);
    }

}
function cats_or_prods(id){
    
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == id){
            return true;
        }
    }
    return false;
}
function open_productos(id){
    
    var categoria = get_categoria(id);

    if(version == 0){
        // MOBILE
        pop_up('pop_cats');
        $('.pop_cats .lista').html('');
        if(categoria.productos){
            for(var j=0, jlen=categoria.productos.length; j<jlen; j++){
                $('.pop_cats .lista').append(html_home_productos(get_producto(categoria.productos[j])));
            }
        }

    }
    if(version == 1){
        // WEB
        $('.cont_productos').html('');   
        if(categoria.productos){
            for(var j=0, jlen=categoria.productos.length; j<jlen; j++){
                $('.cont_productos').append(html_home_productos(get_producto(categoria.productos[j])));
            }
        }
    }

}
function categorias_base(n){
    
    var categorias = data.catalogos[catalogo].categorias;

    if(version == 0){
        // MOBILE
        pop_up('pop_cats');
        $('.pop_cats .lista').html('');
        for(var i=0, ilen=categorias.length; i<ilen; i++){
            if(categorias[i].parent_id == n && categorias[i].ocultar == 0){
                $('.pop_cats .lista').append(html_home_categorias(categorias[i]));  
            }
        }

    }
    if(version == 1){
        // WEB
        $('.cont_categorias').html('');
        $('.cont_productos').html('');
        for(var i=0, ilen=categorias.length; i<ilen; i++){
            if(categorias[i].parent_id == n && categorias[i].ocultar == 0){
                $('.cont_categorias').append(html_home_categorias(categorias[i]));  
            }
        }

    }
    
}
function html_home_productos(obj){
    
    var Div = create_element_class('producto');
    Div.onclick = function(){ add_carro_producto(obj.id_pro); };

    var Divinfopro = create_element_class('cont_info_pro valign');

    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    var Divdescripcion = create_element_class_inner('descripcion', obj.descripcion);

    Divinfopro.appendChild(Divnombre);
    Divinfopro.appendChild(Divdescripcion);
    Div.appendChild(Divinfopro);

    return Div;
    
}
function html_home_categorias(obj){
    
    var Div = create_element_class('categoria');
    if(obj.tipo == 0){
        Div.onclick = function(){ open_categoria(obj.id_cae) };
    }
    if(obj.tipo == 1){
        Div.onclick = function(){ add_carro_promocion(obj.id_cae) };
    }

    var Divinfocat = create_element_class('cont_info_cat valign');

    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    var Divdescripcion = create_element_class_inner('descripcion', obj.descripcion);

    Divinfocat.appendChild(Divnombre);
    Divinfocat.appendChild(Divdescripcion);
    Div.appendChild(Divinfocat);

    return Div;
    
}
function np_close(){
    
    $('.pop_up').hide();
    $('.pop').hide();
    global_telefono = "";
    
}
function add_carro_producto(id_pro){

    var pedidos = get_pedidos();
    var producto = get_producto(id_pro);
    var item_carro = { id_pro: parseInt(id_pro) };
    var aux = [];
    var le = 0;

    if(producto.hasOwnProperty('preguntas')){
        item_carro.preguntas = [];
        for(var k=0, klen=producto.preguntas.length; k<klen; k++){
            aux = get_preguntas(producto.preguntas[k]);
            item_carro.preguntas.push(aux);
        }
    }

    pedidos[seleccionado].total = parseInt(pedidos[seleccionado].total) + parseInt(producto.precio);
    pedidos[seleccionado].carro.push(item_carro);
    pedidos[seleccionado].cambios = 1;
    listar_pedidos(pedidos);

    if(producto.hasOwnProperty('preguntas')){
        le = pedidos[seleccionado].carro.length - 1;
        mostrar_pregunta(le);
    }

}
function get_preguntas(id_pre){

    var preguntas = data.catalogos[catalogo].preguntas;
    for(var i=0, ilen=preguntas.length; i<ilen; i++){
        if(id_pre == preguntas[i].id_pre){
            return preguntas[i];
        }
    }
    return null;

}
function mostrar_pregunta(i){

    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    var producto = get_producto(pedido.carro[i].id_pro);

    if(producto.hasOwnProperty('preguntas')){
        
        pop_up('pop_pre');
        $('.pop_pre .titulo h1').html(producto.nombre);
        $('.pop_pre .titulo h2').html(producto.descripcion);
        $('.pop_pre .lista').html(html_preguntas_producto(i));

    }

}
function html_preguntas_producto(i){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    var carro = pedido.carro;
    
    var html = create_element_class('s_pregunta');
    html.setAttribute('data-pos', i);

    for(var k=0, klen=carro[i].preguntas.length; k<klen; k++){
        
        var e_pregunta = create_element_class('e_pregunta');
        e_pregunta.setAttribute('data-pos', k);
        
        var pregunta_titulo = create_element_class_inner('pregunta_titulo', carro[i].preguntas[k].nombre);
        e_pregunta.appendChild(pregunta_titulo);
        
        
        for(var m=0, mlen=carro[i].preguntas[k].valores.length; m<mlen; m++){
            
            var titulo_v_pregunta = create_element_class_inner('titulo_v_pregunta', carro[i].preguntas[k].valores[m].nombre);                        
            var v_pregunta = create_element_class('v_pregunta');
            v_pregunta.setAttribute('data-pos', m);
            v_pregunta.setAttribute('data-cant', carro[i].preguntas[k].valores[m].cantidad);

            for(var n=0, nlen=carro[i].preguntas[k].valores[m].valores.length; n<nlen; n++){
                
                var n_pregunta = document.createElement('div');
                if(carro[i].preguntas[k].valores[m].hasOwnProperty('seleccionados')){
                    if(carro[i].preguntas[k].valores[m].seleccionados.indexOf(carro[i].preguntas[k].valores[m].valores[n]) != -1){
                        n_pregunta.className = 'n_pregunta selected';
                    }else{
                        n_pregunta.className = 'n_pregunta';
                    }
                }else{
                    n_pregunta.className = 'n_pregunta';
                }
                n_pregunta.innerHTML = carro[i].preguntas[k].valores[m].valores[n];
                n_pregunta.onclick = function(){ select_pregunta(this) };
                v_pregunta.appendChild(n_pregunta);
                
            }
            
            e_pregunta.appendChild(titulo_v_pregunta);
            e_pregunta.appendChild(v_pregunta);
            
        }
        html.appendChild(e_pregunta);
        
    }
    return html;
    
}
function select_pregunta(that){
    
    var parent = $(that).parent();
    var cantidad = parent.attr('data-cant');
    var seleccionadas = parent.find('.selected').length;
    var diff = cantidad - seleccionadas;

    if($(that).hasClass('selected')){
        $(that).removeClass('selected');
    }
    if(cantidad == 1 && !$(that).hasClass('selected')){
        parent.find('.selected').eq(0).removeClass('selected');
        $(that).addClass('selected');
    }
    if(cantidad > 1 && !$(that).hasClass('selected') && diff > 0){
        $(that).addClass('selected');
    }
    
}
function tiene_pregunta(carro){
    
    if(carro.preguntas){
        for(var k=0, klen=carro.preguntas.length; k<klen; k++){
            for(var j=0, jlen=carro.preguntas[k].valores.length; j<jlen; j++){
                var valores = carro.preguntas[k].valores[j];
                if(valores.seleccionados){
                    if(valores.seleccionados.length < valores.cantidad){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }
    }
    return false;
}
function confirmar_pregunta_productos(that){

    var parent = $(that).parents('.pop');
    var pregunta = parent.find('.s_pregunta');
    var i = pregunta.attr('data-pos');
    var k = 0;
    var m = 0;
    var n = 0;
    var count = 0;
    var cant = 0;
    var valores = [];
    var diff = 0;
    
    var preguntas = pregunta.find('.e_pregunta');
    preguntas.each(function(){
        k = $(this).attr('data-pos');
        $(this).find('.v_pregunta').each(function(){
            m = $(this).attr('data-pos');
            cant = $(this).attr('data-cant');
            count = 0;
            valores = [];
            $(this).find('.n_pregunta').each(function(){
                if($(this).hasClass('selected')){
                    count++;
                    valores.push($(this).html().trim());
                }
            });
            diff = cant - count;
            if(diff < 0){
                alert("HA SELECCIONADO "+Math.abs(diff)+" OPCIONES MAS");
            }
            if(diff > 0){
                alert("FALTA SELECCIONAR "+diff+" OPCIONES");
            }
            if(diff == 0){

                var pedidos = get_pedidos();
                pedidos[seleccionado].carro[i].preguntas[k].valores[m].seleccionados = valores;
                listar_pedidos(pedidos);                
                
                var t_pregunta = -1;
                for(var m=0, mlen=pedidos[seleccionado].carro.length; m<mlen; m++){
                    if(tiene_pregunta(pedidos[seleccionado].carro[m])){
                        t_pregunta = m;
                    }
                }
                if(t_pregunta >= 0){
                    mostrar_pregunta(t_pregunta);
                }else{
                    $('.pop_up').hide();
                    $('.pop').hide();
                    //ver_detalle_carro(seleccionado);
                }
                
            }
        });
    });
    
}
function ver_pedido(index){

    pop_up('pop_pedido');
    $('#l_direccion').hide();

    if(index == -1){

        $('.pop_pedido .titulo h1').html("Ingresar Nuevo Pedido");
        $('.pop_pedido .titulo h2').html("");

        $('#id_ped').val(0);
        $('#seleccionado').val(-1);
        $('#id_puser').val(0);
        $('#id_pdir').val(0);
        $('#nombre').val("");
        $('#telefono').val("+569");
        $('#despacho option[value=0]').attr('selected', 'selected');
        $('#m_direccion').hide();

        $('#lat').val(0);
        $('#lng').val(0);
        $('#direccion').val("");
        $('#calle').val("");
        $('#num').val(0);
        $('#depto').val("");
        $('#comuna').val("");
        $('#costo').val(0);
        $('#comentarios').val("");

        $('#pre_wasabi').prop("checked", false);
        $('#pre_gengibre').prop("checked", false);
        $('#pre_soya').prop("checked", false);
        $('#pre_teriyaki').prop("checked", false);
        $('#pre_palitos option[value=0]').attr('selected', 'selected');

        $('.tipo_pago_2').hide();
        $('.tipo_pago_3').hide();
        
    }

    if(index > -1){

        var pedidos = get_pedidos();
        var pedido = pedidos[index];

        if(pedido.num_ped > 0){
            var titulo = "Pedido #"+pedido.num_ped;
        }else{
            var titulo = "Pedido no Guardado";
        }

        $('.pop_pedido .titulo h1').html(titulo);
        $('.pop_pedido .titulo h2').html("");

        $('#id_ped').val(pedido.id_ped);
        $('#seleccionado').val(index);
        $('#id_puser').val(pedido.id_puser);
        $('#id_pdir').val(pedido.id_pdir);
        $('#nombre').val(pedido.nombre);
        $('#telefono').val(pedido.telefono);
        $('#despacho option[value='+pedido.despacho+']').attr('selected', 'selected');
        
        if(pedido.despacho == 1){
            $('#m_direccion').show();
        }
        
        $('#lat').val(pedido.lat);
        $('#lng').val(pedido.lng);
        $('#direccion').val(pedido.direccion);
        $('#calle').val(pedido.calle);
        $('#num').val(pedido.num);
        $('#depto').val(pedido.depto);
        $('#comuna').val(pedido.comuna);
        $('#costo').val(pedido.costo);
        $('#comentarios').val("");
        
        $('.tipo_pago_2').show();
        $('.tipo_pago_3').show();
        $('#monto').val("");
        $('#vuelto').val("");

        if(pedido.pre_wasabi == 1){ $('#pre_wasabi').prop("checked", true) }else{ $('#pre_wasabi').prop("checked", false) }
        if(pedido.pre_gengibre == 1){ $('#pre_gengibre').prop("checked", true) }else{ $('#pre_gengibre').prop("checked", false) }
        if(pedido.pre_soya == 1){ $('#pre_soya').prop("checked", true) }else{ $('#pre_soya').prop("checked", false) }
        if(pedido.pre_teriyaki == 1){ $('#pre_teriyaki').prop("checked", true) }else{ $('#pre_teriyaki').prop("checked", false) }

        seleccionado = index;
        listar_pedidos();

    }

    if(pedido_height == 0){
        var h1 = $('.cont_ped_input1').outerHeight() || 0;
        var h2 = $('.cont_ped_input2').outerHeight() || 0;
        if(h1 < h2){ $('.cont_ped_input1').height(h2); }
        if(h1 > h2){ $('.cont_ped_input2').height(h1); }
        pedido_height = 1;
    }
    
}
function ver_pedido_aux(index){

    $('.t_direcciones').html("");
    $('.p1 .n_stitle').html("");

    if(index >= 0){
        
        $('#id_pdir').val(pedido.id_pdir);
        $('#direccion').val(pedido.direccion);
        $('#depto').val(pedido.depto);
        $('#calle').val(pedido.calle);
        $('#num').val(pedido.num);
        $('#comuna').val(pedido.comuna);
        $('#lat').val(pedido.lat);
        $('#lng').val(pedido.lng);
        $('#costo').val(pedido.costo);

        if(pedido.despacho == 0){
            $('#despacho option[value=0]').attr('selected', 'selected');
            $('.t_despacho').hide();
            $('.t_repartidor').hide();
        }
        if(pedido.despacho == 1){
            $('#despacho option[value=1]').attr('selected', 'selected');
            $('.t_despacho').show();
            $('.t_repartidor').show();
        }

        if(pedido.pre_wasabi == 1){ $('#pre_wasabi').attr('checked', 'checked') }else{ $('#pre_wasabi').attr('checked', '') }
        if(pedido.pre_gengibre == 1){ $('#pre_gengibre').attr('checked', 'checked') }else{ $('#pre_gengibre').attr('checked', '') }
        if(pedido.pre_soya == 1){ $('#pre_soya').attr('checked', 'checked') }else{ $('#pre_soya').attr('checked', '') }
        if(pedido.pre_teriyaki == 1){ $('#pre_teriyaki').attr('checked', 'checked') }else{ $('#pre_teriyaki').attr('checked', '') }
        $('#pre_palitos option[value='+pedido.pre_palitos+']').attr('selected', 'selected');
        $('#id_mot option[value='+pedido.id_mot+']').attr('selected', 'selected');
        
    }
    if(index == -1){

        $('#id_pdir').val(0);
        $('#direccion').val("");
        $('#depto').val("");
        $('#calle').val("");
        $('#num').val(0);
        $('#comuna').val("");
        $('#lat').val(0);
        $('#lng').val(0);
        $('#costo').val(0);

        $('#despacho option[value=1]').attr('selected', 'selected');
        $('.t_despacho').show();
        $('.t_repartidor').show();

        $('#pre_wasabi').attr('checked', '');
        $('#pre_gengibre').attr('checked', '');
        $('#pre_soya').attr('checked', '');
        $('#pre_teriyaki').attr('checked', '');
        $('#pre_palitos option[value=0]').attr('selected', 'selected');
        $('#id_mot option[value=0]').attr('selected', 'selected');

    }
    
    $('.pop_up').show();
    $('.p1').show();
    
}
function telefono_keyup(e){

    var telefono = e.value;
    if(telefono.length >= 12 && telefono != global_telefono){

        global_telefono = telefono;
        $('#l_direccion').hide();
        $('.t_direcciones').html('');
        $('.pop_pedido .titulo h2').html('Buscando..');

        var send = { accion: 'get_users_pedido', telefono: telefono };
        $.ajax({
            url: "/ajax/",
            type: "POST",
            data: send,
            success: function(data){

                if(data.cantidad == 0){
                    $('.pop_pedido .titulo h2').html('No se encontro registro');
                }
                if(data.cantidad > 0){
                    $('.pop_pedido .titulo h2').html('Usuario encontrado, direcciones: '+data.cantidad);
                    $('#id_puser').val(data.id_puser);
                    $('#nombre').val(data.nombre);
                    $('#l_direccion').show();
                    $('.t_direcciones').html(html_pedidos_direcciones(data.direcciones));
                }

            }, error: function(e){
                $('.pop_pedido .titulo h2').html('Error de comunicacion');
            }
        });

    }

}
function monto_keyup(e){

    var monto = e.value;
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    var total = monto - pedido.costo - pedido.total;
    if(total > 0){
        $('#vuelto').val(formatNumber.new(total, "$"));
    }

}
function del_pdir(that){

    var direccion = $(that).parent();
    var id = direccion.attr('id_pdir');

    var send = { id_pdir: id, accion: 'del_pos_pedido' };
    $.ajax({
        url: "/ajax/",
        type: "POST",
        data: send,
        success: function(data){
            direccion.remove();
        }, error: function(){}
    });

}
function html_pedidos_direcciones(direcciones){

    var Div = create_element_class('pedido_direcciones');

    for(var i=0, ilen=direcciones.length; i<ilen; i++){

        var pdir = create_element_class('pedido_direccion');
        pdir.setAttribute('id_pdir', direcciones[i].id_pdir);
        pdir.setAttribute('direccion', direcciones[i].direccion);
        pdir.setAttribute('calle', direcciones[i].calle);
        pdir.setAttribute('num', direcciones[i].num);
        pdir.setAttribute('depto', direcciones[i].depto);
        pdir.setAttribute('lat', direcciones[i].lat);
        pdir.setAttribute('lng', direcciones[i].lng);
        pdir.setAttribute('comuna', direcciones[i].comuna);

        var direccion_nom = create_element_class_inner('md_direccion valign', direcciones[i].calle+' '+direcciones[i].num+' '+direcciones[i].depto);
        direccion_nom.onclick = function(){ select_pdir(this) };
        var direccion_del = create_element_class('md_borrar valign');
        direccion_del.onclick = function(){ del_pdir(this) };

        var cont = create_element_class('cont');

        var l1 = create_element_class('l1');
        var l2 = create_element_class('l2');

        cont.appendChild(l1);
        cont.appendChild(l2);

        direccion_del.appendChild(cont);

        pdir.appendChild(direccion_nom);
        pdir.appendChild(direccion_del);
        
        Div.appendChild(pdir);

    }

    return Div;

}
function select_pdir(that){
    
    var lat = $(that).parent().attr('lat');
    var lng = $(that).parent().attr('lng');

    var send = { lat: lat, lng: lng, accion: 'get_despacho' };
 
    $.ajax({
        url: "/ajax/",
        type: "POST",
        data: send,
        success: function(data){
            
            if(data.op == 1){
                
                $('.t_direcciones').html("");
                $('#id_pdir').val($(that).parent().attr('id_pdir'));
                $('#direccion').val($(that).parent().attr('direccion'));
                $('#depto').val($(that).parent().attr('depto'));
                $('#costo').val(data.precio);
                $('#l_direccion').hide();
                $('#m_direccion').show();
                $('#despacho option[value=1]').attr('selected', 'selected');

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
function change_despacho(that){

    var value = $(that).val();
    if(value == 0){
        $('#m_direccion').hide();
    }
    if(value == 1){
        $('#m_direccion').show();
    }

}
function change_tipo_pago(that){

    var value = $(that).val();
    if(value == 1){
        $('.tipo_pago_2').show();
        $('.tipo_pago_3').show();
    }
    if(value == 2){
        $('.tipo_pago_2').hide();
        $('.tipo_pago_3').hide();
    }
    if(value == 3){
        $('.tipo_pago_2').hide();
        $('.tipo_pago_3').hide();
    }

}
function proceso_categorias(pedido){

    for(var i=0, ilen=pedido.carro.length; i<ilen; i++){
        if(!pedido.carro[i].id_pro){
            seleccionar_productos_categoria_promo(i);
            return false;
        }
    }
    return true;
    
}
function proceso_preguntas(pedido){

    for(var i=0, ilen=pedido.carro.length; i<ilen; i++){
        if(tiene_pregunta(pedido.carro[i])){
            mostrar_pregunta(i);    
            return false;
        }
    }
    return true;
    
}
function promo_restantes(producto, j, tiene_pregunta){

    var pedidos = get_pedidos();
    var Div = create_element_class('restantes_detalle_item clearfix');

    if(producto.numero == 0){
        if(producto.nombre_carro == ""){
            var Nombre = create_element_class_inner('restantes_detalle_nombre', producto.nombre);
        }else{
            var Nombre = create_element_class_inner('restantes_detalle_nombre', producto.nombre_carro);
        }
    }else{
        if(producto.nombre_carro == ""){
            var Nombre = create_element_class_inner('restantes_detalle_nombre', producto.numero+".- "+producto.nombre);
        }else{
            var Nombre = create_element_class_inner('restantes_detalle_nombre', producto.numero+".- "+producto.nombre_carro);
        }
    }
    
    Div.appendChild(Nombre);
    
    var Acciones = create_element_class('restantes_detalle_acciones clearfix');

    var Precio = create_element_class_inner('precio', formatNumber.new(parseInt(producto.precio), "$"));
    Acciones.appendChild(Precio);
    
    var carro = pedidos[seleccionado].carro[j];

    if(carro.preguntas){
    
        var Pregunta = create_element_class('pregunta material-icons');
        if(!tiene_pregunta){
            Pregunta.innerHTML = 'more_horiz';
        }else{
            Pregunta.innerHTML = 'help_outline';
        }
        Pregunta.onclick = function(){ mostrar_pregunta(j) };
        Acciones.appendChild(Pregunta);
    
    }else{
        
        var Espacio = create_element_class('espacio');
        Acciones.appendChild(Espacio);
        
    }
    
    var Accion = create_element_class_inner('accion material-icons', 'close');
    Accion.onclick = function(){ delete_pro_carro(j) };
    Acciones.appendChild(Accion);
    
    Div.appendChild(Acciones);
    return Div;
    
}
function ver_detalle_carro(index){

    var pedidos = get_pedidos();
    var pedido = pedidos[index];
    seleccionado = index;
    listar_pedidos();
    
    if(proceso_categorias(pedido)){
        
        var total = 0;
        var html = create_element_class('process_carro');
        $('.pop_detalle .titulo h1').html("Listado de Productos");
        var promo, process_carro_promo, promo_detalle, promo_info, promo_precio, promo_delete, producto, count;
        
        for(var i=0, ilen=pedido.promos.length; i<ilen; i++){

            promo = get_categoria(pedido.promos[i].id_cae);
            total = total + parseInt(promo.precio);
            
            process_carro_promo = create_element_class('process_carro_promo');
            
            promo_detalle = create_element_class('promo_detalle');
            promo_info = create_element_class_inner('promo_info', promo.nombre);
            promo_precio = create_element_class_inner('promo_precio', formatNumber.new(parseInt(promo.precio), "$"));
            promo_delete = create_element_class_inner('promo_delete material-icons', 'close');
            promo_delete.setAttribute('promo-pos', i);
            promo_delete.onclick = function(){ delete_promo(this, promo.precio) };
            
            process_carro_promo.appendChild(promo_info);
            process_carro_promo.appendChild(promo_precio);
            process_carro_promo.appendChild(promo_delete);
            
            for(var j=0, jlen=pedido.carro.length; j<jlen; j++){
                if(pedido.carro[j].promo == i){
                    count++;
                    producto = get_producto(pedido.carro[j].id_pro);
                    promo_detalle.appendChild(promo_carros(producto, j));
                }
            }
            
            process_carro_promo.appendChild(promo_detalle);
            html.appendChild(process_carro_promo);
            
        }

        var restantes = false;
        var process_carro_restantes = create_element_class('process_carro_restantes');
        
        for(var i=0, ilen=pedido.carro.length; i<ilen; i++){
            if(!pedido.carro[i].hasOwnProperty('promo')){
                var pro = get_producto(pedido.carro[i].id_pro);
                process_carro_restantes.appendChild(promo_restantes(pro, i, tiene_pregunta(pedido.carro[i])));
                total = total + parseInt(pro.precio);
                restantes = true;
            }
        }
        
        if(restantes){ 
            html.appendChild(process_carro_restantes);
        }

        if(pedido.costo > 0){
            $('.pop_detalle .sub').html(formatNumber.new(parseInt(pedido.costo), "$"));
        }else{
            $('.pop_detalle .sub').html("");
        }
        
        $('.pop_detalle .total').html(formatNumber.new(parseInt(total), "$"));
        $('.pop_detalle .lista').html(html);
        pop_up('pop_detalle');
        
    }

}
function add_carro_promocion(id_cae){
    
    var pedidos = get_pedidos();
    var promo = get_categoria(id_cae);
    var tiene_cats = -1;
    
    pedidos[seleccionado].promos.push({ id_cae: id_cae });
    var num_promo = pedidos[seleccionado].promos.length - 1;

    if(promo.categorias){
        for(var i=0, ilen=promo.categorias.length; i<ilen; i++){
            pedidos[seleccionado].carro.push({id_cae: parseInt(promo.categorias[i].id_cae), cantidad: parseInt(promo.categorias[i].cantidad), promo: num_promo });
            tiene_cats = pedidos[seleccionado].carro.length - 1;
        }
    }
    if(promo.productos){
        for(var i=0, ilen=promo.productos.length; i<ilen; i++){
            for(var j=0, jlen=promo.productos[i].cantidad; j<jlen; j++){
                var producto = get_producto(promo.productos[i].id_pro);
                var item_carro = { id_pro: parseInt(promo.productos[i].id_pro), promo: num_promo };
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                pedidos[seleccionado].carro.push(item_carro);
            }
        }
    }

    pedidos[seleccionado].total = parseInt(pedidos[seleccionado].total) + parseInt(promo.precio);
    pedidos[seleccionado].cambios = 1;
    listar_pedidos(pedidos);
    categorias_base(0);
    if(proceso_categorias(pedidos[seleccionado])){
        if(proceso_preguntas(pedidos[seleccionado])){
            $('.pop_up').hide();
            $('.pop').hide();
        }
    }
    
}
function seleccionar_productos_categoria_promo(i){

    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    
    var id_cae = pedido.carro[i].id_cae;
    var cantidad = pedido.carro[i].cantidad;
    var categoria = get_categoria(id_cae);
    
    pop_up('pop_pro_cat');
    $('.pop_pro_cat .titulo h1').html(categoria.nombre);
    $('.pop_pro_cat .titulo h2').html('Debe seleccionar '+cantidad+' productos');
    $('.pop_pro_cat .lista').html(html_seleccionar_productos_categoria_promo(categoria, i, cantidad));
    
}
function html_seleccionar_productos_categoria_promo(categoria, i, cantidad){
    
    var producto;
    var pro_cat_item, pro_cat_item_select, pro_cat_item_nombre, select, option;
    
    
    if(categoria.productos){
        
        var html = create_element_class('pro_cat_promo');
        html.setAttribute('data-pos', i);
        html.setAttribute('data-cantidad', cantidad);
        
        for(var i=0, ilen=categoria.productos.length; i<ilen; i++){
            
            producto = get_producto(categoria.productos[i]);            
            pro_cat_item = create_element_class('pro_cat_item clearfix');
            pro_cat_item_select = create_element_class('pro_cat_item_select');
            
            select = document.createElement("select");
            select.id = categoria.productos[i];
            select.className = 'select_promo';
            select.onchange = function(){ confirmar_productos_promo(false); };
            
            for(var j=0; j<=cantidad; j++){
                option = document.createElement("option");
                option.value = j;
                option.text = j;
                select.appendChild(option);
            }
            
            pro_cat_item_select.appendChild(select);
            pro_cat_item.appendChild(pro_cat_item_select);
            
            pro_cat_item_nombre = create_element_class_inner('pro_cat_item_nombre', producto.numero + '.- ' + producto.nombre);
            pro_cat_item.appendChild(pro_cat_item_nombre);
            html.appendChild(pro_cat_item);
            
        }
        
    }
    return html;
    
}
function confirmar_productos_promo(bool){
    
    var count = 0;
    var arr = [];
    var parent = $('.pop_pro_cat');
    
    var cantidad = parent.find('.pro_cat_promo').attr('data-cantidad');
    var carro_pos = parent.find('.pro_cat_promo').attr('data-pos');
    var producto;
    var item_carro;
    
    parent.find('.pro_cat_item').each(function(){
        count = count + parseInt($(this).find('.select_promo').val());
        arr.push({id_pro: parseInt($(this).find('.select_promo').attr('id')), cantidad: parseInt($(this).find('.select_promo').val())});
    });
    
    if(count == cantidad){
        
        var pedidos = get_pedidos();
        var aux_promo = pedidos[seleccionado].carro[carro_pos].promo;
        pedidos[seleccionado].carro.splice(carro_pos, 1);
        for(var i=0, ilen=arr.length; i<ilen; i++){
            for(var j=0, jlen=arr[i].cantidad; j<jlen; j++){
                
                producto = get_producto(arr[i].id_pro);
                item_carro = { id_pro: parseInt(arr[i].id_pro), promo: aux_promo };
                
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                pedidos[seleccionado].carro.push(item_carro);
                
            }
        }
        listar_pedidos(pedidos);
        if(proceso_categorias(pedidos[seleccionado])){
            if(proceso_preguntas(pedidos[seleccionado])){
                $('.pop_up').hide();
                $('.pop').hide();
            }
        }
        
    }else{
        
        if(bool){
            var diff = cantidad - count;
            if(diff == 1){
                alert("FALTA 1 PRODUCTO");
            }
            if(diff > 1){
                alert("FALTA "+diff+" PRODUCTOS");
            }
            if(diff == -1){
                alert("SOBRA 1 PRODUCTO");
            }
            if(diff < -1){
                alert("SOBRA "+Math.abs(diff)+" PRODUCTOS");
            }
        }
        
    }
     
}
function promo_carros(producto, j){
    
    var pedidos = get_pedidos();
    var Div = create_element_class('promo_detalle_item clearfix');
    
    if(producto.numero == 0){
        if(producto.nombre_carro == ""){
            var Nombre = create_element_class_inner('promo_detalle_nombre', producto.nombre);
        }else{
            var Nombre = create_element_class_inner('promo_detalle_nombre', producto.nombre_carro);
        }
    }else{
        if(producto.nombre_carro == ""){
            var Nombre = create_element_class_inner('promo_detalle_nombre', producto.numero + '.- ' + producto.nombre);
        }else{
            var Nombre = create_element_class_inner('promo_detalle_nombre', producto.numero + '.- ' + producto.nombre_carro);
        }
    }
    Div.appendChild(Nombre);
    
    var Acciones = create_element_class('promo_detalle_acciones clearfix');
    var carro = pedidos[seleccionado].carro[j];

    if(carro.preguntas){
        
        var Accion = create_element_class('accion material-icons');
        Accion.onclick = function(){ mostrar_pregunta(j) };
        if(tiene_pregunta(carro)){
            Accion.innerHTML = 'help_outline';
        }else{
            Accion.innerHTML = 'more_horiz';
        }
        Acciones.appendChild(Accion);
        
    }
    
    Div.appendChild(Acciones);
    return Div;
    
}
function delete_pro_carro(i){
    
    var pedidos = get_pedidos();
    var producto = get_producto(pedidos[seleccionado].carro[i].id_pro);
    pedidos[seleccionado].total = parseInt(pedidos[seleccionado].total) - parseInt(producto.precio);
    pedidos[seleccionado].carro.splice(i, 1);
    pedidos[seleccionado].cambios = 1;
    listar_pedidos(pedidos);
    ver_detalle_carro(seleccionado);
    
}
function delete_promo(that, precio){
    
    var pedidos = get_pedidos();
    var i = $(that).attr('promo-pos');
    pedidos[seleccionado].promos.splice(i, 1);

    var carro = pedidos[seleccionado].carro;
    pedidos[seleccionado].carro = [];
    
    for(var j=0; j < carro.length; j++){
        if(!carro[j].hasOwnProperty('promo') || carro[j].promo != i){
           if(carro[j].promo > i){
               carro[j].promo = carro[j].promo - 1;
           }
           pedidos[seleccionado].carro.push(carro[j]); 
        }
    }

    pedidos[seleccionado].total = parseInt(pedidos[seleccionado].total) - parseInt(precio);
    pedidos[seleccionado].cambios = 1;
    listar_pedidos(pedidos);
    ver_detalle_carro(seleccionado);
    
}
function done_pedido(){

    var id_ped = $('#id_ped').val();
    var select = $('#seleccionado').val();
    var p_wasabi = ($('#pre_wasabi').is(':checked')) ? 1 : 0 ;
    var p_gengibre = ($('#pre_gengibre').is(':checked')) ? 1 : 0 ;
    var p_soya = ($('#pre_soya').is(':checked')) ? 1 : 0 ;
    var p_teriyaki = ($('#pre_teriyaki').is(':checked')) ? 1 : 0 ;
    var p_palitos = $('#pre_palitos').val();
    var id_puser = parseInt($('#id_puser').val());
    var nombre = $('#nombre').val();
    var telefono = $('#telefono').val();
    var despacho = parseInt($('#despacho').val());
    var costo = parseInt($('#costo').val());

    var id_pdir = $('#id_pdir').val();
    var direccion = $('#direccion').val();
    var depto = $('#depto').val();
    var calle = $('#calle').val();
    var num = $('#num').val();
    var comuna = $('#comuna').val();
    var lat = $('#lat').val();
    var lng = $('#lng').val();
    var id_mot = $('#id_mot').val();
    var comentarios = $('#comentarios').val();

    if(id_ped == 0 && select != seleccionado){

        var obj = pedido_obj();
        obj.id_ped = 0;
        obj.num_ped = 0;
        obj.pedido_code = '';
        obj.tipo = 0;
        obj.alert = '';
        obj.estado = 0;
        obj.cambios = 1;
        obj.despacho = despacho;
        obj.carro = [];
        obj.promos = [];
        obj.total = 0;
        obj.costo = costo;
        obj.pre_wasabi = p_wasabi;
        obj.pre_gengibre = p_gengibre;
        obj.pre_palitos = p_palitos;
        obj.pre_soya = p_soya;
        obj.pre_teriyaki = p_teriyaki;
        obj.id_mot = id_mot;
        obj.verificado = 0;
        obj.comentarios = comentarios;
        
        obj.id_puser = id_puser;
        obj.nombre = nombre;
        obj.telefono = telefono;

        obj.id_pdir = id_pdir;
        obj.direccion = direccion;
        obj.calle = calle;
        obj.num = num;
        obj.depto = depto;
        obj.lat = lat;
        obj.lng = lng;
        obj.comuna = comuna;
        add_pedido(obj, 1);

    }
    if(id_ped > 0){
        
        var pedidos = get_pedidos();
        pedidos[seleccionado].id_puser = id_puser;
        pedidos[seleccionado].nombre = nombre;
        pedidos[seleccionado].telefono = telefono;

        pedidos[seleccionado].despacho = despacho;
        pedidos[seleccionado].cambios = 1;
        
        pedidos[seleccionado].id_pdir = id_pdir;
        pedidos[seleccionado].direccion = direccion;
        pedidos[seleccionado].depto = depto;
        pedidos[seleccionado].lat = lat;
        pedidos[seleccionado].lng = lng;
        pedidos[seleccionado].calle = calle;
        pedidos[seleccionado].num = num;
        pedidos[seleccionado].comuna = comuna;

        pedidos[seleccionado].costo = costo;

        pedidos[seleccionado].pre_wasabi = p_wasabi;
        pedidos[seleccionado].pre_gengibre = p_gengibre;
        pedidos[seleccionado].pre_soya = p_soya;
        pedidos[seleccionado].pre_teriyaki = p_teriyaki;
        pedidos[seleccionado].pre_palitos = p_palitos;

        pedidos[seleccionado].id_mot = id_mot;
        listar_pedidos(pedidos);
        
    }

    $('.pop_up').hide();
    $('.pop').hide();
    
}
function add_pedido(obj, n){
        
    var aux = [];
    aux.push(obj);
    var pedidos = get_pedidos();
    if(pedidos.length > 0){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            aux.push(pedidos[i]);
        }
    }
    if(n == 2){
        seleccionado = seleccionado + 1;
        if($('.pop_pedido').is(':visible')){
            $('#seleccionado').val(parseInt($('#seleccionado').val()) + 1);
        }
    }
    if(n == 1){
        seleccionado = 0;
    }
    
    listar_pedidos(aux);
    
}
function guardar_pedido(index){

    var pedidos = get_pedidos();
    var pedido = pedidos[index];
    seleccionado = index;

    if(proceso_categorias(pedido)){
        if(proceso_preguntas(pedido)){
            if(pedido.carro.length > 0 || pedido.promos.length > 0){
                if(pedidos[index].cambios == 1){
                    var send = { pedido: JSON.stringify(pedido), accion: 'set_web_pedido' };
                    $.ajax({
                        url: "/ajax/",
                        type: "POST",
                        data: send,
                        success: function(info){
                            if(pedidos[index].id_ped == 0){
                                pedidos[index].id_ped = info.id_ped;
                                pedidos[index].num_ped = info.num_ped;
                                pedidos[index].pedido_code = info.pedido_code;
                            }
                            pedidos[index].alert = info.alert;
                            //pedidos[index].total = get_precio_carro(pedido);
                            if(tipo_comanda == 0 || tipo_comanda == 1){
                                window.open(get_url(pedido, 1), '_blank').focus();
                            }
                            pedidos[index].cambios = 0;
                            listar_pedidos(pedidos);
                        }, error: function(){}
                    });
                }
                if(pedidos[index].cambios == 0){
                    window.open(get_url(pedido, 0), '_blank').focus();
                }
            }else{
                pedidos[index].alert = "Carro Vacio";
                listar_pedidos(pedidos);
            }
        }
    }
    

}
function get_url(pedido, cambios){

    if(dns == 0){
        var url = 'http://'+ip+'/';
    }
    if(dns == 1){
        if(ssl == 0){
            var url = 'http://'+dominio+'/';
        }
        if(ssl == 1){
            var url = 'https://'+dominio+'/';
        }
    }
    if(cambios == 0){
        if(tipo_comanda == 0){
            url += 'detalle/';
        }
        if(tipo_comanda == 1){
            url += 'detalle1/';
        }
    }
    if(cambios == 1){
        if(tipo_comanda == 0){
            url += 'detalle_n/';
        }
        if(tipo_comanda == 1){
            url += 'detalle_n1/';
        }
    }
    url += pedido.pedido_code;
    return url;

}
function agregar_pedido(id){

    var send = { id_ped: id, accion: 'get_pos_pedidos' };
    $.ajax({
        url: "/ajax/",
        type: "POST",
        data: send,
        success: function(data){

            var obj = pedido_obj();
            obj.id_ped = data.id_ped;
            obj.num_ped = data.num_ped;
            obj.pedido_code = data.pedido_code;
            obj.tipo = 1;
            obj.alert = '';
            obj.estado = 0;
            obj.cambios = 0;
            obj.despacho = data.despacho;
            obj.carro = data.carro;
            obj.promos = data.promos;
            obj.total = data.total;
            obj.costo = data.costo;
            obj.pre_wasabi = data.pre_wasabi;
            obj.pre_gengibre = data.pre_gengibre;
            obj.pre_palitos = data.pre_palitos;
            obj.pre_soya = data.pre_soya;
            obj.pre_teriyaki = data.pre_teriyaki;
            obj.comentarios = comentarios;

            //obj.id_mot = id_mot;
            //obj.verificado = 0;
            
            obj.id_puser = data.id_puser;
            obj.nombre = data.nombre;
            obj.telefono = data.telefono;

            obj.id_pdir = data.id_pdir;
            obj.direccion = data.direccion;
            obj.calle = data.calle;
            obj.num = data.num;
            obj.depto = data.depto;
            obj.lat = data.lat;
            obj.lng = data.lng;
            obj.comuna = data.comuna;
            add_pedido(obj, 2);
            sound(aud1);
        
        }, error: function(){}
    });
    
}
function sound(aud){
    aud.play();
    var playPromise = aud.play();
    if (playPromise !== null){
        playPromise.catch(() => { aud.play() })
    }
}
function btn_mapa(){
    pop_up('pop_mapa');
}
function btn_setting(){
    pop_up('pop_setting');
}
function btn_chat(){
    pop_up('pop_chat');
}
var formatNumber = {
    separador: ".", // separador para los miles
    sepDecimal: ',', // separador para los decimales
    formatear:function (num){
        num +='';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft +splitRight;
    },
    new: function(num, simbol){
        this.simbol = simbol ||'';
        return this.formatear(num);
    }
}