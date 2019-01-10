$(document).ready(function(){
    set_pedidos(pedidos);
    socket_init();
    listar_pedidos();
    modificar_horas();
});
var seleccionado = 0;
var categoria = 0;
var catalogo = 0;
var crear_nuevo = 0;
var estados = ['Enviado', 'Recepcionado', 'Preparando', 'Empaque', 'Despacho'];
var tiempos = { retiro: 900, despacho: 3600 };

function actualizar_seleccionado(){
    
}
function add_carro_producto(id_pro){

    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    pedido.carro.push({id_pro: parseInt(id_pro)});
    set_pedidos(pedidos);
    listar_pedidos();

}
function add_carro_promocion(id_cae){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    var promo = get_categoria(id_cae);

    if(promo.categorias){
        for(var i=0, ilen=promo.categorias.length; i<ilen; i++){
            pedido.promos.carro.push({id_cae: parseInt(promo.categorias[i].id_cae), cantidad: parseInt(promo.categorias[i].cantidad)});
        }
    }
    if(promo.productos){
        for(var i=0, ilen=promo.productos.length; i<ilen; i++){
            for(var j=0, jlen=promo.productos[i].cantidad; j<jlen; j++){
                var producto = get_producto(promo.productos[i].id_pro);
                var item_carro = { id_pro: parseInt(promo.productos[i].id_pro) };
                if(producto.preguntas){
                    item_carro.preguntas = [];
                    for(var k=0, klen=producto.preguntas.length; k<klen; k++){
                        item_carro.preguntas.push(get_preguntas(producto.preguntas[k]));
                    }
                }
                pedido.carro.push(item_carro);
            }
        }
    }

    set_pedidos(pedidos);
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
    
    $('.lista_productos').html('');    
    var categoria = get_categoria(id);
    if(categoria.productos){
        for(var j=0, jlen=categoria.productos.length; j<jlen; j++){
            $('.lista_productos').append(html_home_productos(get_producto(categoria.productos[j])));
        }
    }
    
}
function html_home_productos(obj){
    
    var Div = create_element_class('producto');
    Div.onclick = function(){ add_carro_producto(obj.id_pro); };
    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    Div.appendChild(Divnombre);
    return Div;
    
}
function categoria_padre(){
    if(categoria != 0){
        var cat = get_categoria(categoria);
        open_categoria(cat.parent_id);
    }else{
        open_categoria(0);
    }
}
function modificar_horas(){
    
    var pedidos = get_pedidos();
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){

            var fecha_ahora = Math.round(new Date().getTime()/1000);
            var time = (pedidos[i].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
            var diff = Math.round((pedidos[i].fecha + time - fecha_ahora)/60);
            $('.lista_pedidos').find('.pedido').eq(i).find('.t_tiempo').find('.t_nombre').html(diff);

        }
    }
    setTimeout(modificar_horas, 60000);
    
}

function socket_init(){
    
    var socket = io.connect('http://35.196.220.197:80', { 'forceNew': true });
    socket.on('local-'+local_code, function(id_ped) {
        agregar_pedido(id_ped);
    });
    socket.on('connect', function() {
        $('.alert_socket').hide();
    });
    socket.on('disconnect', function() {
        $('.alert_socket').show();
    });
    
}
var time = new Date().getTime();
function last(){
    
    
    setTimeout(last, 10000);
}
function start(){
    
    $.ajax({
        url: "ajax/start.php",
        type: "POST",
        data: send,
        success: function(data){},
        error: function(e){}
    });
    
}
function agregar_pedido(id){
    
    var send = { id_ped: id };
    $.ajax({
        url: "ajax/get_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            var info = JSON.parse(data);
            nuevo(info[0]);
        }, error: function(e){
            console.log(e);
        }
    });
    
}
function set_pedido(index, that){
    
    seleccionado = index;
    var count = 0;
    categorias_base(0);
    $(that).parents('.lista_pedidos').find('.pedido').each(function(){
        if(count == index){
            $(this).addClass('seleccionado');
        }else{
            $(this).removeClass('seleccionado');
        }
        count++;
    });
    
}
function categorias_base(n){
    
    $('.lista_categorias').html('');
    $('.lista_productos').html('');
    categoria = n;
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == n && categorias[i].ocultar == 0){
            $('.lista_categorias').append(html_home_categorias(categorias[i]));  
        }
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
function listar_pedidos(){
    $('.lista_pedidos').html('');
    var pedidos = JSON.parse(localStorage.getItem("pedidos")) || false;
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            $('.lista_pedidos').append(html_home_pedidos(pedidos[i], i));
        }
    }
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
function html_home_pedidos(obj, index){
    
    var total = 0;
    var pro, cat, carro, promos;
    
    if(obj.carro){
        obj.carro.forEach(function(carro_item, index){
            pro = get_producto(carro_item.id_pro);
            total = total + parseInt(pro.precio);
        });
    }

    if(obj.promos){
        obj.promos.forEach(function(promo_item, index){
            cat = get_categoria(promo_item.id_cae);
            total = total + parseInt(cat.precio);
        });
    }
    
    if(seleccionado == index){
        categorias_base(index);
        var Div = create_element_class('pedido seleccionado');
    }else{
        var Div = create_element_class('pedido');
    }
    
    var p_num = create_element_class_inner('p_num', 'Pedido #'+obj.id_ped);
    var p_estado = create_element_class_inner('p_estado', 'Abierto');
    var p_precio = create_element_class_inner('p_precio', '$'+total);
    var p_cont = create_element_class('p_cont');
    p_cont.onclick = function(){ set_pedido(index, this) };
    
    var btn_mod = create_element_class('btn_mod');
    btn_mod.onclick = function(){ ver_pedido(index, this) };
    
    var btn_open = create_element_class('btn_open');
    btn_open.onclick = function(){ ver_comanda(index) };
    
    var btn_carro = create_element_class('btn_carro');
    btn_carro.onclick = function(){ ver_detalle_carro(index) };
    
    Div.appendChild(p_cont);
    Div.appendChild(p_num);
    Div.appendChild(p_estado);
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
        var t_nombre = create_element_class_inner('t_nombre', '24');
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
function ver_detalle_carro(index){
    console.log("INDEX: "+index);
}
function ver_comanda(index){
    var pedidos = get_pedidos();
    var code = pedidos[index].pedido_code;
    window.open("/restaurants/detalle.php?code="+code, 'Imprimir Ctrl+P').focus();
}
function html_home_categorias(obj){
    
    var Div = create_element_class('categoria');
    if(obj.tipo == 0){
        Div.onclick = function(){ open_categoria(obj.id_cae) };
    }
    if(obj.tipo == 1){
        Div.onclick = function(){ add_carro_promocion(obj.id_cae) };
    }
    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    Div.appendChild(Divnombre);
    return Div;
    
}
function np_close(that){
    
    $('.pop_up').hide();
    $(that).parent().hide();
    
}
function ver_pedido(index, that){
    
    // MOSTRAR INDEX
    var pedidos, pedido;
    if(index >= 0){
        
        set_pedido(index, that);
        pedidos = get_pedidos();
        pedido = pedidos[index];
        if(pedido.id_ped == 0){
            $('.n_title').html("PEDIDO AUN NO GUARDADO");
        }
        if(pedido.id_ped > 0){
            $('.n_title').html("Pedido #"+pedido.id_ped);
        }
        crear_nuevo = 0;
        
    }
    if(index == -1){
        
        pedido = pedido_obj();
        $('.n_title').html("Ingresar Nuevo Pedido");
        crear_nuevo = 1;
    
    }
    
    console.log(pedido);
    
    $('#nombre').val(pedido.nombre);
    $('#telefono').val(pedido.telefono);
    
    if(pedido.despacho == 0){
        $('#despacho option[value=0]').attr('selected', 'selected');
        $('.t_despacho').hide();
    }
    if(pedido.despacho == 1){
        $('#despacho option[value=1]').attr('selected', 'selected');
        $('.t_despacho').show();
    }
    
    if(pedido.pre_wasabi == 1){ $('#pre_wasabi').attr('checked', 'checked') }else{ $('#pre_wasabi').attr('checked', '') }
    if(pedido.pre_gengibre == 1){ $('#pre_gengibre').attr('checked', 'checked') }else{ $('#pre_gengibre').attr('checked', '') }
    if(pedido.pre_embarazadas == 1){ $('#pre_embarazadas').attr('checked', 'checked') }else{ $('#pre_embarazadas').attr('checked', '') }
    if(pedido.pre_soya == 1){ $('#pre_soya').attr('checked', 'checked') }else{ $('#pre_soya').attr('checked', '') }
    if(pedido.pre_teriyaki == 1){ $('#pre_teriyaki').attr('checked', 'checked') }else{ $('#pre_teriyaki').attr('checked', '') }
    $('#pre_palitos option[value='+pedido.pre_palitos+']').attr('selected', 'selected');
    $('#id_mot option[value='+pedido.id_mot+']').attr('selected', 'selected');
    
    $('.pop_up').show();
    $('.nuevo_pedido').show();
    
}
function nuevo_pedido(){
    
    $('#nombre').val('');
    $('.pop_up').show();
    $('.nuevo_pedido').show();
    
}
function done_pedido(){

    if(crear_nuevo == 0){
        
        console.log("MODIFICAR EXISTENTE");
        var pedidos = get_pedidos();
        pedidos[seleccionado].nombre = $('#nombre').val();
        pedidos[seleccionado].telefono = $('#telefono').val();
        pedidos[seleccionado].despacho = $('#despacho').val();
        pedidos[seleccionado].direccion = $('#direccion').val();
        pedidos[seleccionado].depto = $('#depto').val();
        
        pedidos[seleccionado].pre_wasabi = ($('#pre_wasabi').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_gengibre = ($('#pre_gengibre').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_embarazadas = ($('#pre_embarazadas').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_soya = ($('#pre_soya').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_teriyaki = ($('#pre_teriyaki').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_palitos = $('#pre_palitos').val();
        
        pedidos[seleccionado].id_mot = $('#id_mot').val();
        
        set_pedidos(pedidos);
        guardar_pedido(seleccionado);
        
    }
    if(crear_nuevo == 1){
        
        console.log("CREAR NUEVO");
        var obj = pedido_obj();
        obj.id_ped = 0;
        
        obj.nombre = $('#nombre').val();
        obj.telefono = $('#telefono').val();
        obj.despacho = $('#despacho').val();
        
        obj.direccion = $('#direccion').val();
        obj.depto = $('#depto').val();
        
        obj.pre_wasabi = ($('#pre_wasabi').is(':checked') ? 1 : 0 );
        obj.pre_gengibre = ($('#pre_gengibre').is(':checked') ? 1 : 0 );
        obj.pre_embarazadas = ($('#pre_embarazadas').is(':checked') ? 1 : 0 );
        obj.pre_soya = ($('#pre_soya').is(':checked') ? 1 : 0 );
        obj.pre_teriyaki = ($('#pre_teriyaki').is(':checked') ? 1 : 0 );
        obj.pre_palitos = $('#pre_palitos').val();
        
        obj.id_mot = $('#id_mot').val();
        
        add_pedido(obj);
        guardar_pedido(0);
        
    }
    
    $('.nuevo_pedido').hide();
    $('.pop_up').hide();
    
}
function nuevo(data){

    var obj = pedido_obj();
    obj.id_ped = data.id_ped;
    obj.pedido_code = data.pedido_code;
    obj.tipo = 1;
    obj.estado = 0;
    obj.despacho = data.despacho;
    obj.nombre = data.nombre;
    obj.telefono = data.telefono;
    obj.carro = data.carro;
    obj.promos = data.promos;
    obj.pre_wasabi = data.pre_wasabi;
    obj.pre_gengibre = data.pre_gengibre;
    obj.pre_embarazadas = data.pre_embarazadas;
    obj.pre_palitos = data.pre_palitos;
    obj.pre_soya = data.pre_soya;
    obj.pre_teriyaki = data.pre_teriyaki;
    obj.verify_despacho = data.verify_despacho;
    add_pedido(obj);
    listar_pedidos();
    
}
function add_pedido(obj){
    
    console.log(obj);
    
    var aux = [];
    aux.push(obj);
    var pedidos = get_pedidos();
    if(pedidos){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            aux.push(pedidos[i]);
        }
    }
    seleccionado = 0;
    set_pedidos(aux);
    listar_pedidos();
    
}
function set_pedidos(pedidos){
    localStorage.setItem("pedidos", JSON.stringify(pedidos));
}
function get_pedidos(){
    return JSON.parse(localStorage.getItem("pedidos")) || get_pedido_blank();
}
function get_pedido_blank(){
    return [pedido_obj()];
}
function guardar_pedido(index){
        
    var pedidos = get_pedidos();
    var send = { pedido: JSON.stringify(pedidos[index]) };
    
    $.ajax({
        url: "ajax/set_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            
            console.log(JSON.parse(data));
            if(pedidos[index].id_ped == 0){
                var info = JSON.parse(data);
                pedidos[index].id_ped = info.id_ped;
            }
            set_pedidos(pedidos);
            
        }, error: function(e){
            console.log(e);
        }
    });
    
}
function pedido_obj(){
    return { 
        id_ped: 0,
        nombre: '',
        telefono: '',
        pedido_code: '', 
        tipo: 0,
        estado: 0,
        fecha: new Date().getTime(),
        despacho: null,
        carro: [],  
        promos: [], 
        pre_wasabi: 0,
        pre_gengibre: 0,
        pre_embarazadas: 0,
        pre_palitos: 0,
        pre_soya: 0,
        pre_teriyaki: 0,
        verify_despacho: 0,
        nombre: '',
        telefono: '',
        direccion: '',
        lat: '',
        lng: '',
        calle: '',
        num: '',
        depto: '',
        comuna: '',
        id_mot: 0,
    };
}
function change_despacho(that){
    var value = $(that).val();
    var t_despacho = $(that).parents('.data_info').find('.t_despacho');
    
    if(value == 0){
        t_despacho.hide();
    }
    if(value == 1){
        t_despacho.show();
    }
}
function cambiar_estado(index, n, that){

    var pedidos = get_pedidos();
    var aux = parseInt(pedidos[index].estado) + n;

    if(aux >= 0 && aux < estados.length){
        
        pedidos[index].estado = aux;
        var Div = $(that).parents('.p_opciones').find('.p_nombre').html(estados[aux]);
        set_pedidos(pedidos);
        
        var data = { accion: 0, estado: estados[aux] };
        
        var send = { pedido_code: pedidos[index].pedido_code, estado: JSON.stringify(data) };
        $.ajax({
            url: "http://35.196.220.197/cambiar_estado",
            type: "POST",
            data: send,
            success: function(data){
                
            }, error: function(e){
                console.log(e);
            }
        });

    }

}
function cambiar_hora(index, n, that){
    
    var pedidos = get_pedidos();
    pedidos[index].fecha = pedidos[index].fecha + n*60;
    var fecha_ahora = Math.round(new Date().getTime()/1000);
    var tiempo = (pedidos[index].despacho == 1) ? tiempos.despacho : tiempos.retiro ;
    var diff = Math.round((pedidos[index].fecha + tiempo - fecha_ahora)/60);
    $(that).parents('.t_tiempo').find('.t_nombre').html(diff);
    set_pedidos(pedidos);
    
    var data = { accion: 1, fecha: pedidos[index].fecha };
        
    var send = { pedido_code: pedidos[index].pedido_code, estado: JSON.stringify(data) };
    $.ajax({
        url: "http://35.196.220.197/cambiar_estado",
        type: "POST",
        data: send,
        success: function(data){

        }, error: function(e){
            console.log(e);
        }
    });
    
}