<?php
$page = "password";
include("includes/header.php");
?>
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