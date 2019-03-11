<?php

    if($_SERVER['HTTP_HOST'] == "localhost"){
        $path = "C:/AppServ/www/restaurants";
    }else{
        $path = "/var/www/html/restaurants";
    }

    require_once($path."admin/class/core_class.php");
    $core = new Core();
    $info = $core->get_data($_SERVER["HTTP_HOST"]);
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    exit;
    $data = $core->is_pass($_GET["id_user"], $_GET["code"]);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="images/fire.ico" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="/admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="/admin/js/ingreso_aux.js"></script>
        <link rel="stylesheet" href="/admin/css/login.css" type="text/css" media="all">
        <script>

            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_nueva();
                }
            });

            function btn_nueva(){
                
                var btn = $('#nueva');
                btn.prop("disabled", true );
                $.ajax({
                    url: "/admin/ajax/login_back.php",
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
            }
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
                        <div class='input'><input type='text' id='pass_01' value=''></div>
                    </div>
                    <div class='us'>
                        <div class='txt'>Password</div>
                        <div class='input'><input type='text' id='pass_02' value=''></div>
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