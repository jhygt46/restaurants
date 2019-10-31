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
function crear_dominio(){

    var dom = $("input[name*='dominio_msd']").val();
    var correo = $("input[name*='email_msd']").val();
    var telefono = $("input[name*='telefono_msd']").val();
    var dominio = dom.split(".");

    if(validar_email(correo)){
        if(dominio[0] == "www" && dominio.length == 3 && dominio[1].length > 0 && dominio[2].length > 1){
            if(telefono.length == 12 || telefono.length == 13){
                grecaptcha.ready(function(){
                    grecaptcha.execute('6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO', { action: 'contacto' }).then(function(token){
                        document.getElementById("crear_dominio").disabled = true;
                        var send = { accion: 'crear_dominio', nombre: nombre, correo: correo, telefono: telefono, token: token };
                        $.ajax({
                            url: '/ajax/',
                            type: "POST",
                            data: send,
                            success: function(res){
                                console.log(res);
                                if(res.op == 1){}
                                if(res.op == 2){}
                                document.getElementById("crear_dominio").disabled = false;
                            }, error: function(){
                                document.getElementById("crear_dominio").disabled = false;
                            }
                        });
                    });
                });
            }else{}
        }else{}
    }else{}

}
function enviar_contacto(){

    var nombre = $("input[name*='nombre_con']").val();
    var correo = $("input[name*='email_con']").val();
    var telefono = $("input[name*='telefono_con']").val();
    var asunto = $("#asunto_con").val();

    if(validar_email(correo)){
        if(telefono.length == 12 || telefono.length == 13){
            if(nombre != ""){
                grecaptcha.ready(function(){
                    grecaptcha.execute('6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO', { action: 'contacto' }).then(function(token){
                        document.getElementById("enviar_contacto").disabled = true;
                        var send = { accion: 'enviar_contacto', nombre: nombre, correo: correo, telefono: telefono, asunto: asunto, token: token };
                        $.ajax({
                            url: '/ajax/',
                            type: "POST",
                            data: send,
                            success: function(res){
                                console.log(res);
                                if(res.op == 1){}
                                if(res.op == 2){}
                                document.getElementById("enviar_contacto").disabled = false;
                            }, error: function(){
                                document.getElementById("enviar_contacto").disabled = false;
                            }
                        });
                    });
                });
            }else{}
        }else{}
    }else{}
    
}