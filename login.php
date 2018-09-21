<?php
$page = "login";
include("includes/header.php");
?>
<body>
    <input type='hidden' id='accion' value='1'>
    <table cellspacing='0' cellpadding='0' border='0' width='100%' height='100%'>
        <tr>
            <td align='center' valign='middle'>
                <div class='login'>
                    <div class='titulo'></div>
                    <div class='contlogin'>
                        <div class='us'>
                            <div class='txt'>Correo</div>
                            <div class='input'><input type='text' id='user' value='<?php echo $_GET['user']; ?>'></div>
                        </div>
                        <div class='pa'>
                            <div class='txt'>Contrase&ntilde;a</div>
                            <div class='input'><input type='password' id='pass'></div>
                        </div>
                        <div class='button clearfix'>
                            <div class='msg'></div>
                            <div class='btn'><input type='button' id='entrar' value='Entrar'></div>
                        </div>
                    </div>
                    <div class='ltpass'><a href=''>No tiene contrase&ntilde;a?</a></div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>