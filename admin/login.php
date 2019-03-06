<?php

if($_GET["paso"] == "nueva"){

    if($_SERVER["HTTP_HOST"] == "localhost"){
        $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
    }else{
        $path = "/var/www/html/restaurants/";
    }

    require_once($path."admin/class/core_class.php");
    $fireapp = new Core();
    $data = $fireapp->is_pass($_GET["id_user"], $_GET["code"]);

}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="images/fire.ico" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="/admin/js/jquery-2.1.4.min.js"></script>
        <link rel="stylesheet" href="/admin/css/login.css" type="text/css" media="all">
        <script>
            $(document).ready(function(){
            <?php if(!isset($_GET["paso"]) || $_GET["paso"] == "login"){ ?>
                $('#login').click(function(){
                    var btn = $(this);
                    btn.prop("disabled", true);
                    $.ajax({
                        url: "ajax/login_back.php",
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
                                btn.prop("disabled", false );
                            }
                        },
                        error: function(e){
                            btn.prop("disabled", false );
                        }
                    });
                });
            <?php } ?>
            <?php if(isset($_GET["paso"]) && $_GET["paso"] == "recuperar"){ ?>
                $('#recuperar').click(function(){
                    var btn = $(this);
                    btn.prop("disabled", true );
                    $.ajax({
                        url: "ajax/login_back.php",
                        type: "POST",
                        data: "accion=recuperar_password&user="+$('#correo').val(),
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
            <?php } ?>
            <?php if(isset($_GET["paso"]) && $_GET["paso"] == "nueva"){ ?>
                $('#nueva').click(function(){
                    var btn = $(this);
                    btn.prop("disabled", true );
                    $.ajax({
                        url: "ajax/login_back.php",
                        type: "POST",
                        data: "accion=nueva_password&pass_01="+$('#pass_01').val()+"&pass_02="+$('#pass_02').val()+"&id="+$('#id_user').val()+"&code="+$('#code').val(),
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
            <?php } ?>
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
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <?php if(!isset($_GET["paso"]) || $_GET["paso"] == "login"){ ?>
                    <div class='titulo'>LOGIN</div>
                    <div class='titulo2'>MI SITIO DELIVERY</div>
                    <div class='contlogin'>
                        <div class='us'>
                            <div class='txt'>Correo</div>
                            <div class='input'><input type='text' id='user' value=''></div>
                        </div>
                        <div class='pa'>
                            <div class='txt'>Contrase&ntilde;a</div>
                            <div class='input'><input type='password' id='pass'></div>
                        </div>
                        <div class='button clearfix'>
                            <div class='msg'></div>
                            <div class='btn'><input type='button' id='login' value='Entrar'></div>
                        </div>
                    </div>
                    <div class='ltpass'><a href='?paso=recuperar'>No tiene contrase&ntilde;a?</a></div>
                <?php } ?>
                <?php if(isset($_GET["paso"]) && $_GET["paso"] == "recuperar"){ ?>
                    <div class='titulo'>RECUPERAR</div>
                    <div class='titulo2'>MI SITIO DELIVERY</div>
                    <div class='contlogin'>
                        <div class='us'>
                            <div class='txt'>Correo</div>
                            <div class='input'><input type='text' id='correo' value=''></div>
                        </div>
                        <div class='button clearfix'>
                            <div class='msg'></div>
                            <div class='btn'><input type='button' id='recuperar' value='Entrar'></div>
                        </div>
                    </div>
                    <div class='ltpass'><a href='/admin'>Deseo ingresar</a></div>
                <?php } ?>
                <?php if(isset($_GET["paso"]) && $_GET["paso"] == "nueva"){ ?>
                    <div class='titulo'>NUEVA CONTRASEÑA</div>
                    <div class='titulo2'>MI SITIO DELIVERY</div>
                    <div class='contlogin'>
                        <input type='hidden' id='id_user' value='<?php echo $_GET['id_user']; ?>'>
                        <input type='hidden' id='code' value='<?php echo $_GET['code']; ?>'>
                        <div class='us'>
                            <div class='txt'>Password</div>
                            <div class='input'><input type='text' id='pass_01' value=''></div>
                        </div>
                        <div class='us'>
                            <div class='txt'>Password</div>
                            <div class='input'><input type='text' id='pass_02' value=''></div>
                        </div>
                        <div class='button clearfix'>
                            <div class='msg'></div>
                            <div class='btn'><input type='button' id='nueva' value='Entrar'></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>