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
            var carro_prom= <?php echo $info['carro_promo']; ?>;
        </script>
        <link rel="stylesheet" href="<?php echo $info["css_detalle"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <script src="<?php echo $info["js_detalle"]; ?>" type="text/javascript"></script>

    </head>
    <body>
        <div class="titulo">Pedido #<?php echo $info["id_ped"]; ?></div>
        <div class="lista_de_productos"></div>
        <div class="info_cliente">
            <div class="direccion"><?php echo $info["direccion"]; ?></div>
            <?php if($info["depto"] != ""){ ?><div class="depto">Departamento: <?php echo $info["depto"]; ?></div><?php } ?>
            <div class="nombre"><?php echo $info["nombre"]; ?></div>
            <div class="telefono"><?php echo $info["telefono"]; ?></div>
        </div>
        <div class="info_cliente">
            <?php if($info["wasabi"] == 1){ ?><div class="wasabi">Wasabi</div><?php } ?>
            <?php if($info["gengibre"] == 1){ ?><div class="wasabi">Gengibre</div><?php } ?>
            <?php if($info["embarazada"] == 1){ ?><div class="wasabi">Embarazada</div><?php } ?>
            <?php if($info["palitos"] > 0){ ?><div class="wasabi">Palitos: <?php echo $info["palitos"]; ?></div><?php } ?>
        </div>
        <div class="info_cliente">
            <div class="wasabi">Costo Despacho: <?php echo $info["costo"]; ?></div>
            <div class="wasabi">Costo Pedido: <?php echo $info["total"]; ?></div>
            <div class="wasabi">Total: <?php $total = $info["total"] + $info["costo"]; echo $total; ?></div>
        </div>
    </body>
</html>

<?php } ?>