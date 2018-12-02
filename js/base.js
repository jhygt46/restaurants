$(document).ready(function(){
    render_pagina();
    //borrar_carro();
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