<?php

require('admin/class/core_class.php');
$core = new Core();

if(isset($_GET['code'])){
    
    $info = $core->ver_detalle($_GET['code']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $info["titulo"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="<?php echo $info["js_jquery"]; ?>" type="text/javascript"></script>
        <script src="<?php echo $info["js_data"]; ?>" type="text/javascript"></script>
        <script>
            var catalogo = 0;
            var carro = <?php echo $info['carro']; ?>;
            var carro_promo = <?php echo $info['carro_promo']; ?>;
        </script>
        <link rel="stylesheet" href="<?php echo $info["css_detalle"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <script src="<?php echo $info["js_detalle"]; ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="titulo">Pedido #457</div>
        <div class="lista_de_productos"></div>
    </body>
</html>

<?php } ?>