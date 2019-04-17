<?php
session_start();
date_default_timezone_set('America/Santiago');

require('admin/class/core_class.php');
$core = new Core();

$id_loc = (is_numeric($_GET["id_loc"])) ? $_GET["id_loc"] : 0 ;
$local = $core->local($id_loc);
$info = $core->get_data($local['dominio']);
$info_local = $core->socket_code($id_loc, $info['id_gir']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon/locales.ico' />
        <link rel="stylesheet" href="<?php echo $info['path']; ?>/css/reset.css" media="all" />
        <link rel="stylesheet" href="<?php echo $info['path']; ?>/css/cocina.css" media="all" />
        <script src="https://www.izusushi.cl/socket.io/socket.io.js"></script>
        <script src="<?php echo $info['path']; ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="<?php echo $info['path']; ?>/js/data/<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info['path']; ?>/js/cocina.js" type="text/javascript"></script>
        <script>
            var local_code = '<?php echo $info_local['code']; ?>';
        </script>
    </head>
    <body>
        <div class="contenedor">
            <div class="pop_up"></div>
            <div class="lista_pedidos">
                
            </div>
        </div>
    </body>
</html>