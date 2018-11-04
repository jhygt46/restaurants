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
            carro.forEach(function(item_carro){
                var producto = get_producto(item_carro.id_pro);
                console.log(producto);
                var html = document.createElement('div');
                html.className = 'prod_item';
                html.innerHTML = "1.- Buena Nelson";
                $('.list_product').append(html);
                
            });
            function get_producto(id_pro){
                var productos = data.catalogos[catalogo].productos;
                for(var i=0, ilen=productos.length; i<ilen; i++){
                    if(productos[i].id_pro == id_pro){
                        return productos[i];
                    }
                }
            }
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
        <div class="list_product"></div>
    </body>
</html>

<?php } ?>