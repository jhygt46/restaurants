function go_pagina(i){
    
    $('.contenido').hide();
    $('.botones').find('li').each(function(index){
        if(i == index){
            $(this).addClass('selected');
        }else{
            $(this).removeClass('selected');
        }
    });
    $('.empezar').hide();
    $('.clientes').hide();
    $('.contacto').hide();
    if(i == 0){ $('.empezar').show() }
    if(i == 1){ $('.clientes').show() }
    if(i == 2){ $('.contacto').show() }
    
}
function validar_email(email){
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}
function send(){
    var dom = $("input[name*='dominio']").val();
    var correo = $("input[name*='correo']").val();
    var dominio = dom.split(".");
    if(validar_email(correo)){
        if(dominio[0] == "www" && dominio.length == 3 && dominio[1].length > 0 && dominio[2].length > 1){
            return true;
        }else{
            console.log("dominio invalido");
        }
    }else{
        console.log("correo invalido");
    }
    return false;
}
function send2(){
    var nombre = $("input[name*='nombre']").val();
    var correo = $("input[name*='email']").val();
    var telefono = $("input[name*='telefono']").val();
    if(validar_email(correo)){
        if(nombre != "" && telefono != "+569"){
            return true;
        }else{
            console.log("dominio invalido");
        }
    }else{
        console.log("correo invalido");
    }
    return false;
}