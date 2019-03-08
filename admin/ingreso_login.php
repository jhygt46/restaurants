<?php

if($_SERVER['HTTP_HOST'] != "localhost"){
    $path = "https://".$_SERVER["HTTP_HOST"]."/admin/";
    $recuperar = "https://misitiodelivery.cl/recuperar";
}else{
    $path = "http://localhost/restaurants/admin/";
    $recuperar = "http://localhost/restaurants/admin/ingreso_recuperar.php";
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $path; ?>images/fire.ico" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="<?php echo $path; ?>js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="<?php echo $path; ?>js/ingreso_aux.js"></script>
        <link rel="stylesheet" href="<?php echo $path; ?>css/login.css" type="text/css" media="all">
        <script>
            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_login();
                }
            });
            function btn_login(){

                var btn = $('#login');
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
                <div class='titulo'>INGRESO</div>
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
                        <div class='btn'><input type='button' onclick="btn_login()" id='login' value='Entrar'></div>
                    </div>
                </div>
                <div class='ltpass'><a href='<?php echo $recuperar; ?>'>No tiene contrase&ntilde;a?</a></div>
            </div>
        </div>
    </body>
</html>