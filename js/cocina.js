$(document).ready(function(){
    socket_init();
    //$('.lista_pedidos').prepend(createDiv(1, 13405));
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
function ver(id){
    //var id = $(that).parents('.pedido').attr('id');
    console.log(id);
}
function borrar(id){
    //$(that).parents('.pedido').remove();
    console.log(id);
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
function createDiv(valor){

    var html = document.createElement('div');
    html.className = 'pedido';
    html.setAttribute('id', valor.id_ped);

    var titulo = document.createElement("div");
    titulo.className = 'titulo';
    
    var txt = document.createElement("div");
    txt.className = 'txt valign';
    txt.innerHTML = 'Pedido: '+valor.num_ped;
    txt.onclick = function(){
        ver_mas(this);
    }
    titulo.appendChild(txt);

    var ver = document.createElement("div");
    ver.className = 'ver valign';
    ver.onclick = function(){
        ver_mas(this);
    }
    titulo.appendChild(ver);

    var borrar = document.createElement("div");
    borrar.className = 'borrar valign';
    borrar.onclick = function(){
        borrar_mas(this);
    }
    titulo.appendChild(borrar);

    html.appendChild(titulo);

    var detalle = document.createElement("div");
    detalle.className = 'detalle';
    html.appendChild(detalle);

    return html;

}