<?php 

    esconder("login.php");
    $url = url();
    //unset($_COOKIE);
    
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
            $(document).ready(function(){

                $('form').bind('submit', $('form'), function(event){

                    var form = this;

                    event.preventDefault();
                    event.stopPropagation();

                    if (form.submitted) {
                        return;
                    }

                    form.submitted = true;

                    $.ajax({
                        url: path+"admin/login/",
                        type: "POST",
                        data: "accion=login&user="+$('input[name=login_usuario]').val()+"&pass="+$('input[name=login_password]').val(),
                        success: function(data){

                            form.submitted = false;
                            form.submit(); //invoke the save password in browser

                            if(data.op == 1){
                                bien(data.message);
                                setTimeout(function () {
                                    //$(location).attr('href','');
                                }, 2000);
                            }
                            if(data.op == 2){
                                mal(data.message);
                                btn.prop("disabled", false);
                            }
                            if(data.op == 3){
                                
                                bien(data.message);
                                setCookie('id', data.id, 16);
                                setCookie('user_code', data.user_code, 16);
                                setCookie('local_code', data.local_code, 16);
                                setCookie('data', data.data, 16);
                                localStorage.setItem('code', data.code);
                                setTimeout(function(){
                                    $(location).attr('href','/admin/punto_de_venta/');
                                }, 2000);

                            }
                            if(data.op == 4){

                                bien(data.message);
                                setCookie('data', data.data, 16);
                                localStorage.setItem('code', data.code);
                                setTimeout(function(){
                                    $(location).attr('href','/admin/cocina/');
                                }, 2000);

                            }
                        },
                        error: function(e){
                            btn.prop("disabled", false);
                            console.log(e);
                        }
                    });

                    return false;

                });
            });
            $(document).on('keypress',function(e){
                if(e.which == 13){
                    var form = document.getElementById("form");
                    form.submit();
                }
            });
        </script>
    </head>
    <body>
        <div class="cont_login">
            <div class='login vhalign'>
                <div class='titulo'>INGRESO</div>
                <form id="form" class='contlogin' action="login.php" autocomplete="on">
                    <div class='us'>
                        <div class='txt'>Correo</div>
                        <div class='input'><input type='text' name="login_usuario" value="" autocomplete="on"></div>
                    </div>
                    <div class='pa'>
                        <div class='txt'>Contrase&ntilde;a</div>
                        <div class='input'><input type='password' name="login_password" autocomplete="on"></div>
                    </div>
                    <div class='button clearfix'>
                        <div class='msg'></div>
                        <div class='btn'><input type='submit' id='login' value='Entrar'></div>
                    </div>
                </form>
                <div class='ltpass'><a href='<?php echo $url["path"]; ?>admin/recuperar'>No tiene contrase&ntilde;a?</a></div>
            </div>
        </div>
    </body>
</html>