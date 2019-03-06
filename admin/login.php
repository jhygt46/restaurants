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
        <script type="text/javascript" src="/admin/js/login.js"></script>
        <link rel="stylesheet" href="/admin/css/reset.css" type="text/css" media="all">
        <link rel="stylesheet" href="/admin/css/login.css" type="text/css" media="all">
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
                    <div class='ltpass'><a href='/'>Deseo ingresar</a></div>
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