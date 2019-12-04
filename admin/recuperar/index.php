<?php

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off"){
    $location = 'https://misitiodelivery.cl/recuperar';
    header('HTTP/1.1 302 Moved Temporarily');
    header('Location: ' . $location);
}

if(strpos($_SERVER["REQUEST_URI"], "index.php") !== false){
    header('HTTP/1.1 404 Not Found', true, 404);
    include('../../errors/404.html');
    exit;
}

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='../images/favicon.ico' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="../admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../admin/js/ingreso_aux.js"></script>
        <link rel="stylesheet" href="../admin/css/login.css" type="text/css" media="all">
        <script>

            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_recuperar();
                }
            });

            function btn_recuperar(){
                
                var btn = $('#recuperar');
                btn.prop("disabled", true );
                $.ajax({
                    url: "../admin/login/",
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
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <div class='titulo'>RECUPERAR</div>
                <div class='contlogin'>
                    <div class='us'>
                        <div class='txt'>Correo</div>
                        <div class='input'><input type='text' id='correo' value=''></div>
                    </div>
                    <div class='button clearfix'>
                        <div class='msg'></div>
                        <div class='btn'><input type='button'  onclick="btn_recuperar()" id='recuperar' value='Entrar'></div>
                    </div>
                </div>
                <div class='ltpass'><a href='/admin'>Deseo ingresar</a></div>
            </div>
        </div>
    </body>
</html>