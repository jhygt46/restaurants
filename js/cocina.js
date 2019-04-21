$(document).ready(function(){
    socket_init();
    window.addEventListener("resize", listar_pedidos);
});

var pedidos = [];
var catalogo = 0; 

function socket_init(){
    
    var socket = io.connect('https://www.izusushi.cl', { 'secure': true });
    socket.on('cocina-'+local_code, function(id) {
        agregar_pedido(id);
    });
    socket.on('cocina-pos-'+local_code, function(info) {
        modificar_pedido(info);
    });
    socket.on('cocina-rm-'+local_code, function(id) {
        console.log(id);
        borrar_pedido(id);
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
function borrar_pedido(id){
    for(var i=0, ilen=pedidos.length; i<ilen; i++){
        if(pedidos[i].id_ped == id){
            pedidos.splice(i, 1);
            listar_pedidos();
        }
    }
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
    for(var i=0, ilen=pedidos.length; i<ilen; i++){
        if(pedidos[i].id_ped == id){
            pedidos.splice(i, 1);
            ilen--;
            i--;
            listar_pedidos();
        }
    }
}
function listar_pedidos(){
    
    $('.lista_pedidos').html('');
    pedidos.sort(function (a, b){
        return (b.num_ped - a.num_ped);
    })
    pedidos.forEach(function(valor){
        $('.lista_pedidos').prepend(createDiv(valor));
    });
    var pedidos_html = $('.lista_pedidos').find('.pedido');
    
    var width = window.innerWidth;
    var widthDiv = pedidos_html.eq(0).width() + 10;    
    var cant = Math.floor(width/(widthDiv + 15));
    var arrheight = [];
    var arrwidth = [];
    var cont = 0;

    for(var i=0; i<cant; i++){
        arrheight[i] = 0;
        arrwidth[i] = i * widthDiv;
    }

    pedidos_html.each(function(){

        var i = cont % cant;
        var top = arrheight[i] + 10;
        var left = arrwidth[i] + (i + 1) * 10;
        var divheight = $(this).height() + 10;

        $(this).css({ top: top+'px', left: left+'px' });

        arrheight[i] = arrheight[i] + divheight;
        cont++;

    });

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
    /*
    var ver = create_element_class('ver valign');
    ver.onclick = function(){
        ver_mas(this);
    }
    titulo.appendChild(ver);
    */
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
            var categoria = get_categoria(valor.promos[i].id_cae);
            var promo_titulo = create_element_class_inner('promo_titulo', categoria.nombre);
            promocion.appendChild(promo_titulo);
            for(var j=0, jlen=valor.carro.length; j<jlen; j++){
                if(valor.carro[j].promo == i){
                    var producto = get_producto(valor.carro[j].id_pro);
                    var promo_producto = create_element_class('promo_producto');
                    var titulo_producto = create_element_class_inner('titulo_producto', '- '+producto.nombre);
                    promo_producto.appendChild(titulo_producto);
                    if(valor.carro[j].preguntas){
                        for(var f=0, flen=valor.carro[j].preguntas.length; f<flen; f++){
                            for(var k=0, klen=valor.carro[j].preguntas[f].valores.length; k<klen; k++){
                                var pregunta = create_element_class('pregunta');
                                pregunta.innerHTML = valor.carro[j].preguntas[f].valores[k].nombre+": ";
                                if(valor.carro[j].preguntas[f].valores[k].seleccionados !== undefined){
                                    pregunta.innerHTML += valor.carro[j].preguntas[f].valores[k].seleccionados.join('/');
                                }else{
                                    pregunta.innerHTML += '<b>No Definido</b>';
                                }
                                promo_producto.appendChild(pregunta);
                            }
                        }
                    }
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
                var producto = get_producto(valor.carro[i].id_pro);
                var res_producto = create_element_class('restante_producto');
                var titulo_producto = create_element_class_inner('titulo_producto', '- '+producto.nombre);
                res_producto.appendChild(titulo_producto);

                if(valor.carro[i].preguntas){
                    for(var f=0, flen=valor.carro[i].preguntas.length; f<flen; f++){
                        for(var k=0, klen=valor.carro[i].preguntas[f].valores.length; k<klen; k++){
                            var pregunta = create_element_class('pregunta');
                            pregunta.innerHTML = valor.carro[i].preguntas[f].valores[k].nombre+": ";
                            if(valor.carro[i].preguntas[f].valores[k].seleccionados !== undefined){
                                pregunta.innerHTML += valor.carro[i].preguntas[f].valores[k].seleccionados.join('/');
                            }else{
                                pregunta.innerHTML += '<b>No Definido</b>';
                            }
                            res_producto.appendChild(pregunta);
                        }
                    }
                }

                restantes.appendChild(res_producto);
            }
        }
        detalle.appendChild(restantes);
    }
    
    html.appendChild(detalle);
    return html;

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