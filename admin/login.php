<?php 

    if(strpos($_SERVER["REQUEST_URI"], "login.php") !== false){
        header('HTTP/1.1 404 Not Found', true, 404);
        include('../errors/404.html');
        exit;
    }

    unset($_COOKIE);
    
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo $info["path"]; ?>/images/favicon/<?php echo $info["favicon"]; ?>' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="/admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="/admin/js/ingreso.js"></script>
        <link rel="stylesheet" href="/admin/css/login.css" type="text/css" media="all">
        <script>
            $(document).ready(function(){
                $('#user').val(localStorage.getItem("n_correo"));
                localStorage.setItem("n_correo", "");
            });
            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_login();
                }
            });
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <div class='titulo'>INGRESO</div>
                <div class='contlogin'>
                    <div class='us'>
                        <div class='txt'>Correo</div>
                        <div class='input'><input type='text' name="login_usuario" id='user' value=''></div>
                    </div>
                    <div class='pa'>
                        <div class='txt'>Contrase&ntilde;a</div>
                        <div class='input'><input type='password' name="login_password" id='pass'></div>
                    </div>
                    <div class='button clearfix'>
                        <div class='msg'></div>
                        <div class='btn'><input type='button' onclick="btn_login()" id='login' value='Entrar'></div>
                    </div>
                </div>
                <div class='ltpass'><a href='/admin/recuperar'>No tiene contrase&ntilde;a?</a></div>
            </div>
        </div>
    </body>
</html>