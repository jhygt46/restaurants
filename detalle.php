<?php

require('admin/class/core_class.php');
$core = new Core();



if(isset($_GET['code'])){
    
    $info = $core->ver_detalle($_GET['code']);
    if($info['op']){
        
        $id_ped = $info["id_ped"];
        $pedido = json_decode($info["pedido"]);
        $preguntas = $pedido->{'preguntas'};
        $total = intval($pedido->{'total'});
        
        $despacho = $pedido->{'despacho'};
        
        if($despacho == 0){
            $retiro_local = $pedido->{'retiro_local'};
            $costo = 0;
        }
        if($despacho == 1){
            $despacho_domicilio = $pedido->{'despacho_domicilio'};
            $costo = intval($despacho_domicilio->{'costo'});
            $total = $total + $costo;
        }
        
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
            var promos = <?php echo $info['promos']; ?>;
            var pedido = <?php echo $info['pedido']; ?>;
            var costo = <?php echo $costo; ?>;
            var total = <?php echo $total; ?>;
        </script>
        
        <link rel="stylesheet" href="<?php echo $info["css_detalle"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <script src="<?php echo $info["js_detalle"]; ?>" type="text/javascript"></script>
        
    </head>
    <body>
        
        <div class="detalle">
            <?php if($despacho == 1){ ?>
            <div class="verificar">
                <div><?php if($info["verify_despacho"] == 0){ ?>COSTO DESPACHO VERIFICADO<?php } ?></div>
                <div><?php if($info["verify_direccion"] == 0){ ?>DIRECCION VERIFICADA<?php } ?></div>
            </div>
            <?php } ?>
            <div class="titulo txtcen font_01 padding_01 borbottom">Pedido #<?php echo $info["id_ped"]; ?></div>
            <div class="lista_de_productos padding_01 borbottom"></div>
            <?php if($preguntas->{'wasabi'} == 1 || $preguntas->{'gengibre'} == 1 || $preguntas->{'embarazadas'} == 1 || $preguntas->{'palitos'} > 0){ ?>
            <div class="contacto padding_01 borbottom">
                <?php if($preguntas->{'wasabi'} == 1){ ?><div class="txtcen font_04">Wasabi</div><?php } ?>
                <?php if($preguntas->{'gengibre'} == 1){ ?><div class="txtcen font_04">Gengibre</div><?php } ?>
                <?php if($preguntas->{'embarazadas'} == 1){ ?><div class="txtcen font_04">Embarazada</div><?php } ?>
                <?php if($preguntas->{'palitos'} > 0){ ?><div class="txtcen font_04">Palitos: <?php echo $preguntas->{'palitos'}; ?></div><?php } ?>
            </div>
            <?php } ?>            
            <div class="contacto padding_01 borbottom">
                <div class="txtcen font_02"><?php echo $pedido->{'nombre'}; ?></div>
                <div class="txtcen font_03"><?php echo $pedido->{'telefono'}; ?></div>
                <?php 
                    if($despacho == 0){
                ?>
                    <div class="txtcen font_03 strong pddtop_01">Retiro Local Providencia</div>
                <?php } ?>
                    
                <?php 
                    if($despacho == 1){
                    
                ?>
                <div class="txtcen font_03 strong pddtop_01">Despacho a Domicilio</div>
                <div class="txtcen font_03"><?php echo $despacho_domicilio->{'calle'}; ?> <?php echo $despacho_domicilio->{'num'}; ?> <?php if($despacho_domicilio->{'depto'} != ""){ ?>Depto: <?php echo $despacho_domicilio->{'depto'}; ?><?php } ?></div>
                <div class="txtcen font_04"><?php echo $despacho_domicilio->{'comuna'}; ?></div>
                <?php } ?>
            </div>
            <div class="total padding_01 borbottom">
                <?php if($costo > 0){ ?><div class="txtcen font_04">Costo Despacho: $<?php echo number_format($costo, 0, '', '.');; ?></div><?php } ?>
                <div class="txtcen font_06 strong">Total: $<?php echo number_format($total, 0, '', '.'); ?></div>
            </div>
        </div>
        
        
        
        
        
        
        
        
        <!--
        <div class="lista_de_productos"></div>
        <div class="info_cliente">
            <div class="direccion"><?php echo $info["direccion"]; ?></div>
            <?php if($info["depto"] != ""){ ?><div class="depto">Departamento: <?php echo $info["depto"]; ?></div><?php } ?>
            <div class="nombre"><?php echo $pedido->{'nombre'}; ?></div>
            <div class="telefono"><?php echo $pedido->{'telefono'}; ?></div>
        </div>
        <?php if($preguntas->{'wasabi'} == 1 || $preguntas->{'gengibre'} == 1 || $preguntas->{'embarazadas'} == 1 || $preguntas->{'palitos'} > 0){ ?>
        <div class="info_cliente">
            <?php if($preguntas->{'wasabi'} == 1){ ?><div class="wasabi">Wasabi</div><?php } ?>
            <?php if($preguntas->{'gengibre'} == 1){ ?><div class="wasabi">Gengibre</div><?php } ?>
            <?php if($preguntas->{'embarazadas'} == 1){ ?><div class="wasabi">Embarazada</div><?php } ?>
            <?php if($preguntas->{'palitos'} > 0){ ?><div class="wasabi">Palitos: <?php echo $preguntas->{'palitos'}; ?></div><?php } ?>
        </div>
        <?php } ?>
        <div class="info_cliente">
            <div class="wasabi">Costo Despacho: $<?php echo $info["costo"]; ?></div>
            <div class="wasabi">Costo Pedido: $<?php echo $info["total"]; ?></div>
            <div class="wasabi">Total: $<?php $total = $info["total"] + $info["costo"]; echo $total; ?></div>
        </div>
        -->
    </body>
</html>

<?php }else{
    
    echo "<div style='display: block; text-align:center; padding-top: 200px; font-size: 28px'>NO SE ENCONTRO EL PEDIDO<div>";
    
}} ?>