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
var pedidos = get_pedidos();

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
function listar_pedidos(){
    
    $('.cont_lista').html('');
    if(pedidos.length){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            if(pedidos[i].eliminado == 0 && pedidos[i].ocultar == 0){
                $('.cont_lista').append(html_home_pedidos(pedidos[i], i));
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
function html_home_pedidos(obj, index){
    
    var sub_total = get_precio_carro(obj);
    var pedidos = get_pedidos();

    if(obj.despacho == 0){
        obj.costo = 0;
    }

    var total = parseInt(sub_total) + parseInt(obj.costo);

    if(sub_total != obj.total){
        var total_dif = sub_total - parseInt(obj.total);
        obj.alert = "Existe un diferencia de "+formatNumber.new(total_dif, "$");
    }

    if(seleccionado == index){
        if(obj.alert == '' || obj.alert === undefined){
            var Div = create_element_class('pedido pedido_h1 seleccionado');
        }else{
            var Div = create_element_class('pedido pedido_h2 seleccionado');
        }
    }else{
        if(obj.alert == '' || obj.alert === undefined){
            var Div = create_element_class('pedido pedido_h1');
        }else{
            var Div = create_element_class('pedido pedido_h2');
        }
    }
    
    Div.setAttribute('pos', index);
    if(pedidos[index].despacho == 1){
        var p_estado = create_element_class_inner('p_estado', formatNumber.new(parseInt(obj.costo), "$"));
    }
    if(pedidos[index].despacho == 0){
        var p_estado = create_element_class_inner('p_estado', '');
    }
    
    var p_num = create_element_class_inner('p_num', 'Pedido #'+obj.num_ped);
    var p_nom = create_element_class_inner('p_nom', obj.nombre);
    
    var p_precio = create_element_class_inner('p_precio', formatNumber.new(parseInt(total), "$"));
    var p_cont = create_element_class('p_cont');
    p_cont.onclick = function(){ set_pedido(index, this) };
    
    var btn_mod = create_element_class('btn_mod');
    btn_mod.onclick = function(){ ver_pedido(index, this) };
    
    var btn_open = create_element_class('btn_open');
    btn_open.onclick = function(){ guardar_pedido(index, true) };
    
    var btn_carro = create_element_class('btn_carro');
    btn_carro.onclick = function(){ ver_detalle_carro(index, this) };

    if(obj.hasOwnProperty('mensajes_cont')){
        var btn_chat = create_element_class('btn_chat');
        btn_chat.onclick = function(){ abrir_chat(index, this) };
        Div.appendChild(btn_chat);
        if(obj.mensajes_cont > 0){
            var chat_num = create_element_class_inner('chat_num', obj.mensajes_cont);
            Div.appendChild(chat_num);
        }
    }
    if(obj.alert != '' && obj.alert !== undefined){
        var p_alert = create_element_class_inner('p_alert', obj.alert);
        Div.appendChild(p_alert);
    }

    Div.appendChild(p_estado);
    Div.appendChild(p_cont);
    Div.appendChild(p_num);
    Div.appendChild(p_nom);
    Div.appendChild(p_precio);
    Div.appendChild(btn_mod);
    Div.appendChild(btn_open);
    Div.appendChild(btn_carro);
    
    if(obj.tipo == 1){
        
        var estado = create_element_class('p_opciones');
        var anterior = create_element_class('p_anterior');
        anterior.onclick = function(){ cambiar_estado(index, -1, this) };
        var nombre = create_element_class_inner('p_nombre', estados[obj.estado]);
        var siguiente = create_element_class('p_siguiente');
        siguiente.onclick = function(){ cambiar_estado(index, 1, this) };

        estado.appendChild(anterior);
        estado.appendChild(nombre);
        estado.appendChild(siguiente);

        var t_tiempo = create_element_class('t_tiempo');
        var t_anterior = create_element_class('t_anterior');
        t_anterior.onclick = function(){ cambiar_hora(index, -1, this) };
        var t_nombre = create_element_class_inner('t_nombre', '');
        var t_siguiente = create_element_class('t_siguiente');
        t_siguiente.onclick = function(){ cambiar_hora(index, 1, this) };

        t_tiempo.appendChild(t_anterior);
        t_tiempo.appendChild(t_nombre);
        t_tiempo.appendChild(t_siguiente);
        
        Div.appendChild(estado);
        Div.appendChild(t_tiempo);
        
    }

    return Div;
    
}
function get_precio_carro(obj){

    var total = 0;
    
    if(obj.carro){
        obj.carro.forEach(function(carro_item){
            if(carro_item.id_pro && carro_item.promo === undefined){
                var pro = get_producto(carro_item.id_pro);
                if(pro !== undefined){ 
                    total = total + parseInt(pro.precio); 
                }
            }
        });
    }

    if(obj.promos){
        obj.promos.forEach(function(promo_item){
            var cat = get_categoria(promo_item.id_cae);
            total = total + parseInt(cat.precio);
        });
    }
    
    return total;

}
function get_pedidos(){
    return JSON.parse(localStorage.getItem("pedidos")) || get_pedido_blank();
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
        despacho: 1,
        carro: [],  
        promos: [], 
        pre_wasabi: 0,
        pre_gengibre: 0,
        pre_embarazadas: 0,
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
        ocultar: 0
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
    
    categoria = n;
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
function np_close(that){
    
    $('.pop_up').hide();
    $(that).parents('.pop').hide();
    
}

function add_carro_producto(id_pro){

    var producto = get_producto(id_pro);
    var item_carro = { id_pro: parseInt(id_pro) };
    
    if(producto.preguntas){
        item_carro.preguntas = [];
        for(var k=0, klen=producto.preguntas.length; k<klen; k++){
            item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
        }
    }

    pedidos[seleccionado].total = parseInt(pedidos[seleccionado].total) + parseInt(producto.precio);
    pedidos[seleccionado].carro.push(item_carro);
    if(producto.preguntas){
        mostrar_pregunta(pedidos[seleccionado].carro.length - 1);
    }

    listar_pedidos();

}
function get_preguntas(id_pre){

    for(var i=0, ilen=data.catalogos[catalogo].preguntas.length; i<ilen; i++){
        if(id_pre == data.catalogos[catalogo].preguntas[i].id_pre){
            return data.catalogos[catalogo].preguntas[i];
        }
    }
    return null;

}
function mostrar_pregunta(i){

    var pedido = pedidos[seleccionado];
    var producto = get_producto(pedido.carro[i].id_pro);

    if(producto.preguntas){

        pop_up('pop_pre');
        $('.pop_pre .titulo h1').html(producto.nombre);
        $('.pop_pre .titulo h2').html(producto.descripcion);
        $('.pop_pre .lista').html(html_preguntas_producto(i));

    }

}
function html_preguntas_producto(i){
    
    var pedido = pedidos[seleccionado];
    var carro = pedido.carro;
    
    var html = document.createElement('div');
    html.className = 's_pregunta';
    html.setAttribute('data-pos', i);

    for(var k=0, klen=carro[i].preguntas.length; k<klen; k++){
        
        var e_pregunta = document.createElement('div');
        e_pregunta.className = 'e_pregunta';
        e_pregunta.setAttribute('data-pos', k);
        
        var pregunta_titulo = document.createElement('div');
        pregunta_titulo.className = 'pregunta_titulo';
        pregunta_titulo.innerHTML = carro[i].preguntas[k].nombre;
        e_pregunta.appendChild(pregunta_titulo);
        
        
        for(var m=0, mlen=carro[i].preguntas[k].valores.length; m<mlen; m++){
            
            var titulo_v_pregunta = document.createElement('div');
            titulo_v_pregunta.className = 'titulo_v_pregunta';
            titulo_v_pregunta.innerHTML = carro[i].preguntas[k].valores[m].nombre;
                        
            var v_pregunta = document.createElement('div');
            v_pregunta.className = 'v_pregunta';
            v_pregunta.setAttribute('data-pos', m);
            v_pregunta.setAttribute('data-cant', carro[i].preguntas[k].valores[m].cantidad);

            for(var n=0, nlen=carro[i].preguntas[k].valores[m].valores.length; n<nlen; n++){
                
                var n_pregunta = document.createElement('div');
                if(carro[i].preguntas[k].valores[m].seleccionados){
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
                pedidos[seleccionado].carro[i].preguntas[k].valores[m].seleccionados = valores;
                var t_pregunta = -1;
                for(var m=0, mlen=pedidos[seleccionado].carro.length; m<mlen; m++){
                    if(tiene_pregunta(pedidos[seleccionado].carro[m])){
                        t_pregunta = m;
                    }
                }
                if(t_pregunta >= 0){
                    mostrar_pregunta(t_pregunta);
                }else{
                    //ver_detalle_carro(seleccionado, null);
                }
            }
        });
    });
    
}
