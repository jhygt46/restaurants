function setCookie(name, value, hour){
    var expires = "";
    if(hour){
        var date = new Date();
        date.setTime(date.getTime() + (hour*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
/*
function getCookie(name){
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++){
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
*/
function btn_login(){

    console.log("login");

    var btn = $('#login');
    btn.prop("disabled", true);
    $.ajax({
        url: path+"admin/login/",
        type: "POST",
        data: "accion=login&user="+$('#user').val()+"&pass="+$('#pass').val(),
        success: function(data){

            if(data.op == 1){
                bien(data.message);
                setTimeout(function () {
                    $(location).attr('href','');
                }, 2000);
            }
            if(data.op == 2){
                mal(data.message);
                btn.prop("disabled", false);
            }
            if(data.op == 3){
                
                bien(data.message);
                setCookie('id', data.id, 16);
                setCookie('user_code', data.user_code, 16);
                setCookie('local_code', data.local_code, 16);
                setCookie('data', data.data, 16);
                localStorage.setItem('code', data.code);
                setTimeout(function(){
                    $(location).attr('href','/admin/punto_de_venta/');
                }, 2000);

            }
            if(data.op == 4){

                bien(data.message);
                setCookie('data', data.data, 16);
                localStorage.setItem('code', data.code);
                setTimeout(function(){
                    $(location).attr('href','/admin/cocina/');
                }, 2000);

            }
        },
        error: function(e){
            btn.prop("disabled", false);
            console.log(e);
        }
    });
    return false;

}
function btn_recuperar(){
                
    var btn = $('#recuperar');
    btn.prop("disabled", true );
    $.ajax({
        url: path+"admin/login/",
        type: "POST",
        data: "accion=recuperar_password&user="+$('#correo').val(),
        success: function(data){
            if(data.op == 1){
                localStorage.setItem('correo', $('#correo').val());
                $('#correo').val('');
                bien(data.message);
                setTimeout(function () {
                    $(location).attr("href","/admin");
                }, 5000);
            }
            if(data.op == 2){
                mal(data.message);
                btn.prop("disabled", false);
            }
        },
        error: function(e){
            btn.prop("disabled", false);
        }
    });

}
function btn_nueva(){
                
    var btn = $('#nueva');
    btn.prop("disabled", true );
    $.ajax({
        url: path+"admin/login/",
        type: "POST",
        data: "accion=nueva_password&pass_01="+$('#pass_01').val()+"&pass_02="+$('#pass_02').val()+"&id="+$('#id_user').val()+"&code="+$('#code').val(),
        success: function(data){
            if(data.op == 1){
                bien(data.message);
                localStorage.setItem("n_correo", data.correo);
                setTimeout(function () {
                    $(location).attr("href","/admin");
                }, 2000);
            }
            if(data.op == 2){
                mal(data.message);
                btn.prop("disabled", false);
            }     
        },
        error: function(e){
            btn.prop("disabled", false);
        }
    });
}

function bien(msg){
                
    $('.msg').html(msg);
    $('.msg').css("color", "#666");    
    $('#user').css("border-color", "#ccc");
    $('#pass').css("border-color", "#ccc");
    $('#user').css("background-color", "#fcfcfc");
    $('#pass').css("background-color", "#fcfcfc");

}
function mal(msg){   
    
    $('#pass').val("");
    $('.msg').html(msg);
    $('.msg').css("color", "#E34A25");
    $('#user').css("border-color", "#E34A25");
    $('#pass').css("border-color", "#E34A25");
    $('#user').css("background-color", "#FCEFEB");
    $('#pass').css("background-color", "#FCEFEB");
    login1();
    login2();
    login3();
    login2();
    login3();
    login2();
    login3();
    login4();
    
}
function login1(){
    $(".login").animate({
        'padding-left': '+=15px'
    }, 200);
}
function login2(){
    $(".login").animate({
        'padding-left': '-=30px'
    }, 200);
}
function login3(){
    $(".login").animate({
        'padding-left': '+=30px'
    }, 200);
}
function login4(){
    $(".login").animate({
        'padding-left': '-=15px'
    }, 200);
}