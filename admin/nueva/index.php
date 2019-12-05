<?php

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off"){
    $location = 'https://'.$_SERVER["HTTP_HOST"].'/admin/nueva/?id_user='.$_GET['id_user'].'&code='.$_GET['code'];
    header('HTTP/1.1 302 Moved Temporarily');
    header('Location: ' . $location);
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
$correo = $core->is_pass($_GET["id_user"], $_GET["code"]);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo $info["path"]; ?>/images/favicon/<?php echo $info["favicon"]; ?>' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="../admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../admin/js/ingreso.js"></script>
        <link rel="stylesheet" href="../admin/css/login.css" type="text/css" media="all">
        <script>

            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_nueva();
                }
            });
            
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <div class='titulo'>NUEVA CONTRASEÑA</div>
                <div class='contlogin'>
                    <input type='hidden' id='id_user' value='<?php echo $_GET['id_user']; ?>'>
                    <input type='hidden' id='code' value='<?php echo $_GET['code']; ?>'>
                    <div class='us'>
                        <div class='txt'>Password</div>
                        <div class='input'><input type='password' name="pass_01" id='pass_01' value=''></div>
                    </div>
                    <div class='us'>
                        <div class='txt'>Repetir Password</div>
                        <div class='input'><input type='password' name="pass_02" id='pass_02' value=''></div>
                    </div>
                    <div class='button clearfix'>
                        <div class='msg'></div>
                        <div class='btn'><input type='button' onclick="btn_nueva()" id='nueva' value='Entrar'></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>