var seleccionado = null;
var categoia = 0;
var catalogo = 0;
var estados = ['Enviado', 'Recepcionado', 'Preparando', 'Empaque', 'Despacho'];


function get_pedidos(){
    /*
    pedidos.forEach(function(pedido, index){
        if(typeof pedido.carro !== 'object' && pedido.carro !== ''){
            console.log(pedido);
            pedido.carro = JSON.parse(pedido.carro);
        }
        if(typeof pedido.promos !== 'object' && pedido.promos !== ''){
            pedido.promos = JSON.parse(pedido.promos);
        }
        return pedido;
    });
    */
    return pedidos;
    
}

function listar_pedidos(){
    
    console.log("LISTAR");
    $('.lista_pedidos').html('');
    for(var i=0, ilen=pedidos.length; i<ilen; i++){
        if(pedidos[i].id_ped == 0){ guardar_pedido(pedidos[i], i) }
        $('.lista_pedidos').append(html_home_pedidos(pedidos[i], i));
    }

}
function add_nuevo_pedido(id_ped){
    
    var send = { id_ped: id_ped };
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
function socket_init(){
    
    var socket = io.connect('http://35.196.220.197:80', { 'forceNew': true });
    socket.on('local-'+local_code, function(data) {
        add_nuevo_pedido(data);
    });
    socket.on('connect', function() {
        alert("CONNECT...");
    });
    socket.on('disconnect', function() {
        alert("DISCONNECT...");
    });
}
function traer_pedido(){
    
}
function guardar_pedido(pedido, i){
    
    var send = { pedido: JSON.stringify(pedido) };
    
    $.ajax({
        url: "ajax/set_pedido.php",
        type: "POST",
        data: send,
        success: function(data){
            
            var info = JSON.parse(data);
            pedidos[i].id_ped = info.id_ped;
            
        }, error: function(e){
            console.log(e);
        }
    });
    
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
    
    if(seleccionado !== null && seleccionado == index){
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
    btn_mod.onclick = function(){ ver_pedido(index) };
    
    Div.appendChild(p_cont);
    Div.appendChild(p_num);
    Div.appendChild(p_estado);
    Div.appendChild(p_precio);
    Div.appendChild(btn_mod);
    
    if(obj.tipo == 1){
        
        var estado = create_element_class('p_opciones');
        var anterior = create_element_class('p_anterior');
        anterior.onclick = function(){ cambiar_estado(index, -1) };
        var nombre = create_element_class_inner('p_nombre', 'Cocinando');
        var siguiente = create_element_class('p_siguiente');
        siguiente.onclick = function(){ cambiar_estado(index, 1) };

        estado.appendChild(anterior);
        estado.appendChild(nombre);
        estado.appendChild(siguiente);
        
        Div.appendChild(estado);
    }

    return Div;
    
}
function pedido_obj(){
    return { 
        id_ped: 0, 
        pedido_code: '', 
        tipo: 0,
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
    };
}
function done_pedido(){
    
    var despacho = $('#despacho').val();
    console.log(seleccionado);
    
    if(seleccionado > -1){
        
        pedidos[seleccionado].nombre = $('#nombre').val();
        pedidos[seleccionado].telefono = $('#telefono').val();
        pedidos[seleccionado].despacho = despacho;
        pedidos[seleccionado].pre_wasabi = ($('#pre_wasabi').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_gengibre = ($('#pre_gengibre').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_embarazadas = ($('#pre_embarazadas').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_soya = ($('#pre_soya').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_teriyaki = ($('#pre_teriyaki').is(':checked') ? 1 : 0 );
        pedidos[seleccionado].pre_palitos = $('#pre_palitos').val();
        
        if(despacho == 1){
            
            pedidos[seleccionado].direccion = $('#direccion').val();
            pedidos[seleccionado].depto = $('#depto').val();
            pedidos[seleccionado].calle = $('#calle').val();
            pedidos[seleccionado].num = $('#num').val();
            pedidos[seleccionado].comuna = $('#comuna').val();
            pedidos[seleccionado].lat = $('#lat').val();
            pedidos[seleccionado].lng = $('#lng').val();
            
        }
        
        listar_pedidos();
        $('.pop_up').hide();
        
    }
    
    if(seleccionado == -1){
        
        var newObj = {};
        newObj.id_ped = 0;
        newObj.nombre = $('#nombre').val();
        newObj.telefono = $('#telefono').val();
        newObj.despacho = despacho;
        newObj.tipo = 0;
        newObj.carro = [];
        newObj.promos = [];
        
        newObj.pre_wasabi = ($('#pre_wasabi').is(':checked') ? 1 : 0 );
        newObj.pre_gengibre = ($('#pre_gengibre').is(':checked') ? 1 : 0 );
        newObj.pre_embarazadas = ($('#pre_embarazadas').is(':checked') ? 1 : 0 );
        newObj.pre_soya = ($('#pre_soya').is(':checked') ? 1 : 0 );
        newObj.pre_teriyaki = ($('#pre_teriyaki').is(':checked') ? 1 : 0 );
        newObj.pre_palitos = $('#pre_palitos').val();
        newObj.verify_despacho = 0;
        
        
        if(despacho == 1){
            
            newObj.direccion = $('#direccion').val();
            newObj.depto = $('#depto').val();
            newObj.calle = $('#calle').val();
            newObj.num = $('#num').val();
            newObj.comuna = $('#comuna').val();
            newObj.lat = $('#lat').val();
            newObj.lng = $('#lng').val();
            
        }
        
        add_pedido(newObj);
        seleccionado = 0;
        listar_pedidos();
        $('.pop_up').hide();
        
    }
    
}
function nuevo(data){
        
    var obj = pedido_obj();
    obj.id_ped = data.id_ped;
    obj.pedido_code = data.pedido_code;
    obj.tipo = 1;
    obj.despacho = data.despacho;
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
    
    var aux = [];
    aux.push(obj);
    for(var i=0, ilen=pedidos.length; i<ilen; i++){
        aux.push(pedidos[i]);
    }
    pedidos = aux;
    
}
function rm_pedido(n){
    pedidos.splice(n, 1);
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

function cats_or_prods(id){
    
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == id){
            return true;
        }
    }
    return false;
}

function categorias_base(n){
    
    $('.lista_categorias').html('');
    $('.lista_productos').html('');
    var categorias = data.catalogos[catalogo].categorias;
    for(var i=0, ilen=categorias.length; i<ilen; i++){
        if(categorias[i].parent_id == n && categorias[i].ocultar == 0){
            $('.lista_categorias').append(html_home_categorias(categorias[i]));  
        }
    }
    
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
function categoria_padre(){
    if(categoria != 0){
        var cat = get_categoria(categoria);
        open_categoria(cat.parent_id);
    }else{
        open_categoria(0);
    }
}
function open_categoria(id){

    console.log(id);

    if(cats_or_prods(id)){
        categoria = id;
        categorias_base(id);
    }else{
        open_productos(id);
    }

}
function cambiar_estado(index, n){

    var pedidos = get_pedidos();
    var estado_pos = parseInt(pedidos[index].estado) + n;

    if(estado_pos >= 0 && estado_pos < estados.length){
        
        $('.lista_pedidos').find('.pedido').eq(index).find('.p_opciones').find('.p_nombre').html(estados[estado_pos]);
        pedidos[index].estado = estado_pos;
        
        var send = { pedido_code: pedidos[index].pedido_code, estado: estado_pos };

        $.ajax({
            url: "http://35.196.220.197/cambiar_estado",
            type: "POST",
            data: send,
            success: function(data){
                set_pedidos(pedidos);
            }, error: function(e){
                console.log(e);
            }
        });
        
    }
    
}
function np_close(that){
    
    $('.pop_up').hide();
    $(that).parent().hide();
    
}
function borrar_campos(){
    $('#nombre').val('');
    $('#telefono').val('');
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
function borrar_campos(){
    $('#nombre').val('');
    $('#telefono').val('');
    $('#despacho option[value=0]').attr('selected', 'selected');
    $('.t_despacho').hide();
    $('#pre_wasabi').attr('checked', '');
    $('#pre_gengibre').attr('checked', '');
    $('#pre_embarazadas').attr('checked', '');
    $('#pre_palitos option[value=0]').attr('selected', 'selected');
}
function ver_pedido(index){
    
    //console.log("index :"+index);
    seleccionado = index;
    
    borrar_campos();
    if(index > -1){

        var pedido = pedidos[index];
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
        if(pedido.pre_wasabi == 0){ $('#pre_wasabi').attr('checked', '') }
        if(pedido.pre_wasabi == 1){ $('#pre_wasabi').attr('checked', 'checked') }
        
        if(pedido.pre_gengibre == 0){ $('#pre_gengibre').attr('checked', '') }
        if(pedido.pre_gengibre == 1){ $('#pre_gengibre').attr('checked', 'checked') }
        
        if(pedido.pre_embarazadas == 0){ $('#pre_embarazadas').attr('checked', '') }
        if(pedido.pre_embarazadas == 1){ $('#pre_embarazadas').attr('checked', 'checked') }
        
        $('#pre_palitos option[value='+pedido.pre_palitos+']').attr('selected', 'selected');


    }
    $('.pop_up').show();
    $('.nuevo_pedido').show();
    
}
function add_carro_producto(id_pro){
    
    var pedidos = get_pedidos();
    var pedido = pedidos[seleccionado];
    pedido.carro.push({id_pro: id_pro});
    set_pedidos(pedidos);
    
}
function add_carro_promocion(id){
    
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
function html_home_productos(obj){
    
    var Div = create_element_class('producto');
    Div.onclick = function(){ add_carro_producto(obj.id_pro); };
    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    Div.appendChild(Divnombre);
    return Div;
    
}

// AUX CREAR ELEMENTOS // 
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
function get_producto(id_pro){
    var productos = data.catalogos[catalogo].productos;
    for(var i=0, ilen=productos.length; i<ilen; i++){
        if(productos[i].id_pro == id_pro){
            return productos[i];
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