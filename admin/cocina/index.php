<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
esconder_index();
redireccion_ssl();
$url = url();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/default.ico' />
        <link rel="stylesheet" href="/css/reset.css" media="all" />
        <link rel="stylesheet" href="/css/cocina.css" media="all" />
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="<?php echo $url["path"]; ?>data/<?php echo $_COOKIE["giro_code"]; ?>.js" type="text/javascript"></script>
        <script src="<?php echo $url["path"]; ?>js/cocina.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="contenedor">
            <div class="pop_up"></div>
            <div class="lista_pedidos"></div>
        </div>
    </body>
</html>