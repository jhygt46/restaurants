<?php 

    esconder("login.php");  
    
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo $info["path"]; ?>/images/favicon/<?php echo $info["favicon"]; ?>' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $url["path"]; ?>admin/js/login.js"></script>
        <link rel="stylesheet" href="<?php echo $url["path"]; ?>admin/css/login.css" type="text/css" media="all">
        <script>
            var path = '<?php echo $url['path']; ?>';
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
                <form class='form_login' onSubmit="return btn_login()">
                    <div class='data'>
                        <div class='txt'>Correo</div>
                        <div class='input'><input type='text' name="login_usuario" id='user' value=''></div>
                    </div>
                    <div class='data'>
                        <div class='txt'>Contrase&ntilde;a</div>
                        <div class='input'><input type='password' name="login_password" id='pass'></div>
                    </div>
                    <div class='button'>
                        <div class='recordar_checkbox'><input class="vhalign" type="checkbox" name="recordad" id='recordad' value='1'></div>
                        <div class='recordar'><div class="valign">Recordar</div></div>
                        <div class='msg'></div>
                        <div class='btn'><input type='submit' id='login' value='Entrar'></div>
                    </div>
                </form>
                <div class='ltpass'><a href='<?php echo $url["path"]; ?>admin/recuperar'>No tiene contrase&ntilde;a?</a></div>
            </div>
        </div>
    </body>
</html>