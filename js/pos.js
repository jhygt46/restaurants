
var seleccionado = null;
var categoia = 0;
var catalogo = 0;
var estados = ['Enviado', 'Recepcionado', 'Preparando', 'Empaque', 'Despacho'];

function verpedidos(){
    console.log(pedidos);
}

function actualizar_pedidos(){
    
    var pedidos = get_pedidos();
    $('.lista_pedidos').html('');
    pedidos.forEach(function(pedido, index){
        $('.lista_pedidos').append(html_home_pedidos(pedido, index));
    });
    
}
function socket_init(){
    
    actualizar_pedidos();
    var socket = io.connect('http://35.196.220.197:80', { 'forceNew': true });
    socket.on('local-'+local_code, function(data) {
        console.log("DATA SOCKET");
        console.log(data);
        add_pedido(data);
    });

}

function set_pedidos(pedidos){
    localStorage.setItem("pedidos", JSON.stringify(pedidos));
}
function get_pedidos(){
    return JSON.parse(localStorage.getItem("pedidos")) || [];
}
function add_pedido(data){
    var pedidos = get_pedidos();
    pedidos.push(data);
    set_pedidos(pedidos);
    actualizar_pedidos();
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
    $('.nuevo_pedido').hide();
    
}
function html_home_pedidos(obj, index){

    var total = 0;
    var pro, cat;
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
    var Div = create_element_class('pedido');
    
    var p_num = create_element_class_inner('p_num', 'Pedido #476');
    p_num.onclick = function(){ set_pedido(index, this) };
    var p_estado = create_element_class_inner('p_estado', 'Abierto');
    var p_precio = create_element_class_inner('p_precio', '$'+total);
    
    var btn_mod = create_element_class('btn_mod');
    btn_mod.onclick = function(){ ver_pedido(index) };
    
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
function ver_pedido(index){
    
    if(index > -1){
        // VER PEDIDO
        var pedidos = get_pedidos();
        var pedido = pedidos[index];
        
        console.log(pedido);
        $('.ped_direccion').val(pedido.direccion);
        $('.ped_telefono').val(pedido.telefono);
    }
    if(index == -1){
        // NUEVO PEDIDO
        console.log("NUEVO PEDIDO");
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