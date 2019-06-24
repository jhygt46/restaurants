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
            $(document).ready(function(){
                $('#user').val(localStorage.getItem("mail_nuevo"));
            });
            $(document).on('keypress',function(e){
                if(e.which == 13){
                    btn_login();
                }
            });
            function setCookie(name, value, hour){
                var expires = "";
                if(hour){
                    var date = new Date();
                    date.setTime(date.getTime() + (hour*60*60*1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }
            function getCookie(name){
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++){
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }
            function btn_login(){

                var btn = $('#login');
                btn.prop("disabled", true);
                $.ajax({
                    url: "/admin/ajax/login_back.php",
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
                        if(data.op == 3){
                            console.log(data);
                            bien(data.message);
                            setCookie('id', data.id, 16);
                            setCookie('user_code', data.user_code, 16);
                            localStorage.setItem('local_code', data.local_code);
                            setTimeout(function () {
                                //$(location).attr('href','/'+data.url);
                            }, 2000);
                        }
                        if(data.op == 4){
                            bien(data.message);
                            setCookie('data', data.data, 16);
                            localStorage.setItem('local_code', data.local_code);
                            setTimeout(function () {
                                $(location).attr('href','/'+data.url);
                            }, 2000);
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
                <div class='ltpass'><a href='/recuperar'>No tiene contrase&ntilde;a?</a></div>
            </div>
        </div>
    </body>
</html>