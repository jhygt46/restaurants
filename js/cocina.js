$(document).ready(function(){
    socket_init();
});

var pedidos = [];

function socket_init(){
    
    var socket = io.connect('https://www.izusushi.cl', { 'secure': true });
    socket.on('cocina-'+local_code, function(id) {
        agregar_pedido(id);
    });
    socket.on('cocina-pos-'+local_code, function(info) {
        modificar_pedido(info);
    });
    socket.on('connect', function() {
        $('.alert_socket').hide();
    });
    socket.on('disconnect', function() {
        $('.alert_socket').show();
    });
    
}
function modificar_pedido(info){

    if(in_arr(pedidos, info.id_ped)){
        for(var i=0, ilen=pedidos.length; i<ilen; i++){
            if(pedidos[i].id_ped == info.id_ped){
                pedidos[i].carro = info.carro;
                pedidos[i].promos = info.promos;
                listar_pedidos();
            }
        }
    }else{
        pedidos.push({ id_ped: info.id_ped, num_ped: info.num_ped, carro: info.carro, promos: info.promos });
        listar_pedidos();
    }

}
function in_arr(arr, id_ped){

    for(var i=0, ilen=arr.length; i<ilen; i++){
        if(arr[i].id_ped == id_ped){
            return true;
        }
    }
    return false;
}

function agregar_pedido(id){

    var send = { id_ped: id };
    $.ajax({
        url: "ajax/get_cocina.php",
        type: "POST",
        data: send,
        success: function(data){
            
            var info = JSON.parse(data);
            var aux = { id_ped: info.id_ped, num_ped: info.num_ped, carro: JSON.parse(info.carro), promos: JSON.parse(info.promos) }
            pedidos.push(aux);
            listar_pedidos();
            
        }, error: function(e){
            console.log(e);
        }
    });

}
function ver_mas(that){
    var id = $(that).parents('.pedido').attr('id');
    console.log("VER MAS "+id);
}
function borrar_mas(that){
    var id = $(that).parents('.pedido').attr('id');
    console.log("BORRAR MAS "+id);
}
function listar_pedidos(){
    $('.lista_pedidos').html('');
    pedidos.forEach(function(valor){
        $('.lista_pedidos').prepend(createDiv(valor));
    });
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
function createDiv(valor){

    var html = create_element_class('pedido');
    html.setAttribute('id', valor.id_ped);
    var titulo = create_element_class('titulo');
    var txt = create_element_class_inner('txt valign', 'Pedido: '+valor.num_ped);
    txt.onclick = function(){
        ver_mas(this);
    }
    titulo.appendChild(txt);
    var ver = create_element_class('ver valign');
    ver.onclick = function(){
        ver_mas(this);
    }
    titulo.appendChild(ver);
    var borrar = create_element_class('borrar valign');
    borrar.onclick = function(){
        borrar_mas(this);
    }
    titulo.appendChild(borrar);
    html.appendChild(titulo);

    var detalle = create_element_class('detalle');

    if(valor.promos !== undefined){
        for(var i=0, ilen=valor.promos.length; i<ilen; i++){
            var promocion = create_element_class('promocion');
            var promo_titulo = create_element_class('promo_titulo', 'Titulo BUE');
            promocion.appendChild(promo_titulo);
            for(var j=0, jlen=valor.carro.length; j<jlen; j++){
                if(valor.carro[j].promo == i){
                    var promo_producto = create_element_class('promo_producto', 'Producto: '+valor.carro[j].id_pro);
                    promocion.appendChild(promo_producto);
                }
            }
            detalle.appendChild(promocion);
        }
    }

    if(valor.carro !== undefined){
        var restantes = create_element_class('restantes');
        for(var i=0, ilen=valor.carro.length; i<ilen; i++){
            if(!valor.carro[i].hasOwnProperty('promo')){
                var res_producto = create_element_class('promo_producto', 'Producto: '+valor.carro[i].id_pro);
                restantes.appendChild(res_producto);
            }
        }
        detalle.appendChild(restantes);
    }
    
    html.appendChild(detalle);
    return html;

}