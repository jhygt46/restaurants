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

    var dom = $("#dominio_msd").val();
    var correo = $("#email_msd").val();
    var telefono = $("#telefono_msd").val();
    var dominio = dom.split(".");

    if(validar_email(correo)){
        if(dominio[0] == "www" && dominio.length == 3 && dominio[1].length > 0 && dominio[2].length > 1){
            if(telefono.length == 12 || telefono.length == 13){
                grecaptcha.ready(function(){
                    grecaptcha.execute('6LdZp78UAAAAAK56zJAVEkaSupUdCrRhsd1wnKkO', { action: 'contacto' }).then(function(token){
                        document.getElementById("crear_dominio").disabled = true;
                        var send = { accion: 'crear_dominio', dominio: dominio, correo: correo, telefono: telefono, token: token };
                        $.ajax({
                            url: '/ajax/',
                            type: "POST",
                            data: send,
                            success: function(res){
                                console.log(res);
                                if(res.op == 1){ 
                                    $('.formempezar').hide(); 
                                    $('.empezarok').show();
                                    $("#dominio_msd").val("");
                                    $("#email_msd").val(""); 
                                    $("#telefono_msd").val(""); 
                                }
                                if(res.op == 2){
                                    if(res.tipo == 1){
                                        //TELEFONO
                                        $('#telefono_msd_ttl').html(res.mensaje);
                                        $("#telefono_msd").css({border: '1px solid #900'});
                                    }
                                    if(res.tipo == 2){
                                        //NOMBRE
                                        $('#dominio_msd_ttl').html(res.mensaje);
                                        $("#dominio_msd").css({border: '1px solid #900'});
                                    }
                                    if(res.tipo == 3){
                                        //CORREO
                                        $('#email_msd_ttl').html(res.mensaje);
                                        $("#email_msd").css({border: '1px solid #900'});
                                    }
                                }
                                document.getElementById("crear_dominio").disabled = false;
                            }, error: function(){
                                document.getElementById("crear_dominio").disabled = false;
                            }
                        });
                    });
                });
            }else{ 
                $('#telefono_msd_ttl').html("Telefono invalido");
                $("#telefono_msd").css({border: '1px solid #900'});
            }
        }else{ 
            $('#dominio_msd_ttl').html("Dominio invalido");
            $("#dominio_msd").css({border: '1px solid #900'});
        }
    }else{ 
        $('#email_msd_ttl').html("Correo invalido");
        $("#email_msd").css({border: '1px solid #900'});
    }

}
function enviar_contacto(){

    var nombre = $("#nombre_con").val();
    var correo = $("#email_con").val();
    var telefono = $("#telefono_con").val();
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
                                if(res.op == 1){ 
                                    $('.formcontacto').hide(); 
                                    $('.contactook').show();
                                    $("#nombre_con").val("");
                                    $("#email_con").val("");
                                    $("#telefono_con").val("");
                                    $("#asunto_con").val("");
                                }
                                if(res.op == 2){
                                    if(res.tipo == 1){
                                        //TELEFONO
                                        $('#telefono_con_ttl').html(res.mensaje);
                                        $("#telefono_con").css({border: '1px solid #900'});
                                    }
                                    if(res.tipo == 2){
                                        //NOMBRE
                                        $('#nombre_con_ttl').html(res.mensaje);
                                        $("#nombre_con").css({border: '1px solid #900'});
                                    }
                                    if(res.tipo == 3){
                                        //CORREO
                                        $('#email_con_ttl').html(res.mensaje);
                                        $("#email_con").css({border: '1px solid #900'});
                                    }
                                }
                                document.getElementById("enviar_contacto").disabled = false;
                            }, error: function(){
                                document.getElementById("enviar_contacto").disabled = false;
                            }
                        });
                    });
                });
            }else{
                // NOMBRE
                $('#nombre_con_ttl').html("Debe ingresar nombre");
                $("#nombre_con").css({border: '1px solid #900'});
            }
        }else{
            // TELEFONO
            $('#telefono_con_ttl').html("Debe ingresar telefono valido");
            $("#telefono_con").css({border: '1px solid #900'});
        }
    }else{
        // CORREO
        $('#email_con_ttl').html("Debe ingresar correo valido");
        $("#email_con").css({border: '1px solid #900'});
    }
    
}