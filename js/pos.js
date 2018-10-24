var id_ped = 0;
var catalogo = 0;

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
        alert("DATA");
        console.log(data);
    });
    socket.on('disconnect', function(){
        
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
    pedidos.push(objeto_pedidos());
    set_pedidos(pedidos);
    actualizar_pedidos();
    
}
function objeto_pedidos(){
    return {id_ped: 0};
}
function set_pedido(index, that){
    id_ped = index;
    var count = 0;
    categorias_base(0);
    $(that).parent().find('.pedido').each(function(){
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
function open_categoria(id){

    if(cats_or_prods(id)){
        categorias_base(id);
    }else{
        open_productos(id);
    }

}
function html_home_pedidos(obj, index){
    
    var Div = create_element_class('pedido');
    Div.onclick = function(){ set_pedido(index, this) };
    
    var p_num = create_element_class_inner('p_num', 'Pedido #476');
    var p_estado = create_element_class_inner('p_estado', 'Abierto');
    var p_precio = create_element_class_inner('p_precio', '$2.990');
    
    var btn_mod = create_element_class('btn_mod');
    btn_mod.onclick = function(){ modificar_pedido(index) };
    
    Div.appendChild(p_num);
    Div.appendChild(p_estado);
    Div.appendChild(p_precio);
    Div.appendChild(btn_mod);
    return Div;
}
function modificar_pedido(index){
    $('.pop_up').show();
    $('.nuevo_pedido').hide();
    $('.modificar_pedido').show();
}
function html_home_categorias(obj){
    var Div = create_element_class('categoria');
    Div.onclick = function(){ open_categoria(obj.id_cae) };
    var Divnombre = create_element_class_inner('nombre', obj.nombre);
    Div.appendChild(Divnombre);
    return Div;
}
function html_home_productos(obj){
    var Div = create_element_class('producto');
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