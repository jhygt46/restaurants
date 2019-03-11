<?php

    if($_SERVER['HTTP_HOST'] == "localhost"){
        $path = "C:/AppServ/www/restaurants";
    }else{
        $path = "/var/www/html/restaurants";
    }

    require_once($path."/admin/class/core_class.php");
    $core = new Core();
    $info = $core->get_data($_SERVER["HTTP_HOST"]);
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    exit;

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
                    btn_recuperar();
                }
            });

            function btn_recuperar(){
                
                var btn = $('#recuperar');
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