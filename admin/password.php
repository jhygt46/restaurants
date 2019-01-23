<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="images/fire.ico" />
        <script type="text/javascript" src="../../../admin/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="../../../admin/js/login.js"></script>
        <link rel="stylesheet" href="../../../admin/css/reset.css" type="text/css" media="all">
        <link rel="stylesheet" href="../../../admin/css/login.css" type="text/css" media="all">
    </head>
    <body>
        <input type='hidden' id='id' value='<?php echo $_GET["id"]; ?>'>
        <input type='hidden' id='code' value='<?php echo $_GET["code"]; ?>'>    
        <table cellspacing='0' cellpadding='0' border='0' width='100%' height='100%'>
            <tr>
                <td align='center' valign='middle'>
                    <div class='login'>
                        <div class='titulo'></div>
                        <div class='contlogin'>
                            <div class='us'>
                                <div class='txt'>Contrase&ntilde;a</div>
                                <div class='input'><input type='password' id='pass1'></div>
                            </div>
                            <div class='pa'>
                                <div class='txt'>Repetir Contrase&ntilde;a</div>
                                <div class='input'><input type='password' id='pass2'></div>
                            </div>
                            <div class='button clearfix'>
                                <div class='msg'></div>
                                <div class='btn'><input type='button' id='entrar' value='Entrar'></div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>