<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();

$info = $core->get_data($_SERVER["HTTP_HOST"]);

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off"){
    if($info['ssl'] == 0){
        $location = 'https://misitiodelivery.cl/recuperar';
        header('HTTP/1.1 302 Moved Temporarily');
        header('Location: ' . $location);
    }
    if($info['ssl'] == 1){
        $location = 'https://'.$_SERVER['HTTP_HOST']."/recuperar";
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $location);
    }
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo $info["path"]; ?>/images/favicon/<?php echo $info["favicon"]; ?>' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="<?php echo $info['path']; ?>/admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="<?php echo $info['path']; ?>/admin/js/ingreso_aux.js"></script>
        <link rel="stylesheet" href="<?php echo $info['path']; ?>/admin/css/login.css" type="text/css" media="all">
        <script>

            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_recuperar();
                }
            });

            function btn_recuperar(){
                
                var btn = $('#recuperar');
                btn.prop("disabled", true );
                console.log("RECUPERAR");
                $.ajax({
                    url: "<?php echo $info['path']; ?>/admin/ajax/login_back.php",
                    type: "POST",
                    data: "accion=recuperar_password&user="+$('#correo').val(),
                    success: function(data){
                        console.log(data);
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
                        console.log(e);
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