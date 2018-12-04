$(document).ready(function(){
    render_pagina();
    inicio();
    cantidad = get_carro().length;
    $('.cantcart_num').html(cantidad);
});

var menu = 0;
var modal = 0;
var paso = 1;
var catalogo = 0;
var debug = 1;
var mostrar_preguntas = [];
var cantidad = 0;
var pre_promo = 0;
var map_init = 0;
var maps = [];

// FIN BACK BUTTON //

// CLICK OUT //
$(document).click(function (e) {
    if($(e.target).hasClass('cont_modals')){
        hide_modal();
    }
    if($(e.target).hasClass('close')){
        hide_modal();
    }
});

function inicio(){
    
    var pedido = get_pedido();
    var carro = get_carro();
    
    set_pedido(null);
    borrar_carro();
    
    if(pedido.id_ped == 0){
        //set_pedido(null);
        //borrar_carro();
        console.log("NO TIENE PEDIDO");
    }
    if(pedido.id_ped > 0){
        if(pedido.fecha){
            console.log("MOSTRAR PEDIDO");
        }else{
            console.log("BORRAR PEDIDO");
        }
    }
    
    
    
}