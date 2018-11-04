<?php

require('admin/class/core_class.php');
$core = new Core();

if(isset($_GET['code'])){
    
    $info = $core->ver_detalle($_GET['code']);
    print_r($info);

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
            var carro = <?php echo $info['carro']; ?>;
            var carro_promo = <?php echo $info['carro_promo']; ?>;
            console.log(carro);
        </script>
        <style>
            div{
                width: 100%;
                text-align: center;
            }
            .font_a{
                font-size: 28px;
            }
            .bor_bottom{
                border-bottom: 1px solid #000;
            }
            .padding_a{
                
            }
        </style>
    </head>
    <body>
        <div class="font_a bor_bottom">Pedido #457</div>
    </body>
</html>

<?php } ?>