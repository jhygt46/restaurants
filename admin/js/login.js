$(document).ready(function(){

    $('#login').click(function(){
        
        var btn = $(this);
        btn.prop( "disabled", true );
        var user = $('#user').val();
        var pass = $('#pass').val();
        $.ajax({
            url: "ajax/login_back.php",
            type: "POST",
            data: "accion=login&user="+user+"&pass="+pass,
            success: function(data){
                if(data.op == 1){
                    bien(data.message);
                    setTimeout(function () {
                        $(location).attr('href','');
                    }, 2000);
                }
                if(data.op == 2){
                    mal(data.message);
                    btn.prop("disabled", false );
                }
            },
            error: function(e){
                btn.prop("disabled", false );
            }
        });

    });

    $('#recuperar').click(function(){
        
        var btn = $(this);
        btn.prop("disabled", true );
        
        var correo = $('#correo').val();
        
        $.ajax({
            url: "ajax/login_back.php",
            type: "POST",
            data: "accion=recuperar_password&user="+correo,
            success: function(data){

                if(data.op == 1){
                    bien(data.message);
                    setTimeout(function () {
                        $(location).attr('href',"");
                    }, 2000);
                }
                if(data.op == 2){
                    mal(data.message);
                    btn.prop("disabled", false );
                }
            },
            error: function(e){
                btn.prop("disabled", false );
            }
        });

    });

    $('#nueva').click(function(){
        
        var btn = $(this);
        btn.prop("disabled", true );
        
        var pass_01 = $('#pass_01').val();
        var pass_02 = $('#pass_02').val();
        var id = $('#id_user').val();;
        var code = $('#code').val();;

        $.ajax({
            url: "ajax/login_back.php",
            type: "POST",
            data: "accion=nueva_password&pass_01="+pass_01+"&pass_02="+pass_02+"&id="+id+"&code="+code,
            success: function(data){

                if(data.op == 1){
                    bien(data.message);
                    setTimeout(function () {
                        $(location).attr('href',"");
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

    });

});

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

