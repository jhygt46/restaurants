function go_pagina(i){
    
    $('.botones').find('li').each(function(index){
        if(i == index){
            $(this).addClass('selected');
        }else{
            $(this).removeClass('selected');
        }
    });
    
    $('.video').hide();
    if(i == 0){
        $('.empezar').show();
        $('.clientes').hide();
        $('.contacto').hide();
    }
    if(i == 1){
        $('.empezar').hide();
        $('.clientes').show();
        $('.contacto').hide();
    }
    if(i == 2){
        $('.empezar').hide();
        $('.clientes').hide();
        $('.contacto').show();
    }
    
}
function playvideo(){
    var myVideo = document.getElementById("video1");
    if (myVideo.paused){
        $('.playvideo').html("Pausar Video");
        myVideo.play(); 
    }else{
        $('.playvideo').html("Reproducir Video");
        myVideo.pause();
    }
}
function validar_email(email){
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}
function send(){
    var dom = $("input[name*='dominio_msd']").val();
    var correo = $("input[name*='correo_msd']").val();
    var telefono = $("input[name*='telefono_msd']").val();
    var dominio = dom.split(".");
    if(validar_email(correo)){
        if(dominio[0] == "www" && dominio.length == 3 && dominio[1].length > 0 && dominio[2].length > 1){
            if(telefono.length == 12 || telefono.length == 13){
                return true;
            }else{}
        }else{}
    }else{}
    return false;
}
function send2(){
    var nombre = $("input[name*='nombre']").val();
    var correo = $("input[name*='email']").val();
    var telefono = $("input[name*='telefono']").val();
    if(validar_email(correo)){
        if(telefono.length == 12 || telefono.length == 13){
            if(nombre != ""){
                return true;
            }else{}
        }else{}
    }else{}
    return false;
}