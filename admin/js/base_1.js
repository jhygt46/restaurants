$(document).ready(function(){
     
});

window.onresize = function() {
    form_responsive($('.pagina').width());   
}
var menu = 0;
function form_responsive(w){
    if(w !== null){
        if(w < 600){
            $('.pagina').find('form').each(function(){
                $(this).find('fieldset').addClass('resp');
                $(this).find('fieldset').removeClass('normal');
            });
        }
        if(w >= 600){
            $('.pagina').find('form').each(function(){
                $(this).find('fieldset').removeClass('resp');
                $(this).find('fieldset').addClass('normal');
            });
        }
    }
}
function open_perfil(){
    $('.modals').show();
    $('.modal_perfil').show();
    $('.modal_error').hide();
    $('.modal_loading').hide();
}
function menu_toggle(){

    if(menu == 0){
        open_menu();
    }else{
        close_menu();
    }
    
}
function open_menu(){
    $('.menu_left').css({ left: '0px' });
    menu = 1;
}
function close_menu(){
    $('.menu_left').css({ left: '-210px' });
    menu = 0;
}
function closes(that){
    $('.modals').hide();
    $(that).parents('.cont_modal').hide();
}
function open_bloque(that){
    var bloque_lista = $(that).parent().find('.bloque_lista');
    bloque_lista.slideToggle();
}
function navlink(href){
    
    topscroll();
    $.ajax({
        url: href,
        type: "POST",
        data: { w: $(".html").width()},
        beforeSend: function(){
            $(".loading").show();
            $(".error").hide();
        },
        success: function(data, status){
            $(".html").html(data);
            $(".loading").hide();
        },
        error: function(){
            $(".error").show();
            $(".loading").hide();
        },
        complete: function(){
            
            
        }
    });
    return false;
}
function topscroll(){
    $('.cont_contenido').animate({ scrollTop: 0 }, 500);
}
