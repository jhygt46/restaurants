<?php

require('admin/class/core_class.php');
$core = new Core();


if(isset($_GET['code'])){
    
    $info = $core->ver_detalle($_GET['code']);
    
    
    echo "<pre>";
    print_r($info);
    echo "</pre>";
    
    if($info['op']){
        
        $id_ped = $info["id_ped"];
        $pep = $info['pep'];
        
        $despacho = $info['despacho'];
        $total = $info['total'];
        
        if($despacho == 0){
            $costo = 0;
        }
        if($despacho == 1){
            $costo = $info['costo'];
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
            var costo = <?php echo $costo; ?>;
            var total = <?php echo $total; ?>;
        </script>
        
        <link rel="stylesheet" href="<?php echo $info["css_detalle"]; ?>" media="all" />
        <link rel="stylesheet" href="<?php echo $info["css_base"]; ?>" media="all" />
        <script src="<?php echo $info["js_detalle"]; ?>" type="text/javascript"></script>
        
    </head>
    <body onload="//window.print()">
        
        <div class="detalle">
            
            <div class="verificar">
            <?php if($despacho == 1 && false){ ?>
                <?php if($info["verify_despacho"] == 0){ ?><div>ERROR: COSTO DESPACHO NO COINCIDE</div><?php } ?>
                <?php if($info["verify_direccion"] == 0){ ?><div>ERROR: DIRECCION NO CORRESPONDE A COORDENADAS</div><?php } ?>
            <?php } ?>
            </div>
            
            <div class="titulo txtcen font_01 padding_01 borbottom">Pedido #<?php echo $info["id_ped"]; ?></div>
            <div class="lista_de_productos padding_01 borbottom"></div>
            <?php if($info['pre_wasabi'] == 1 || $info['pre_gengibre'] == 1 || $info['pre_embarazadas'] == 1 || $info['pre_palitos'] > 0 || $info['pre_soya'] == 1 || $info['pre_teriyaki'] == 1 || $info['comentarios'] != ""){ ?>
            <div class="contacto padding_01 borbottom">
                
                <?php if($info['pre_wasabi'] == 1){ ?><div class="txtcen font_04">Wasabi</div><?php } ?>
                <?php if($info['pre_gengibre'] == 1){ ?><div class="txtcen font_04">Gengibre</div><?php } ?>
                <?php if($info['pre_embarazadas'] == 1){ ?><div class="txtcen font_04">Embarazadas</div><?php } ?>
                <?php if($info['pre_palitos'] == 1){ ?><div class="txtcen font_04">Palitos</div><?php } ?>
                <?php if($info['pre_soya'] == 1){ ?><div class="txtcen font_04">Soya</div><?php } ?>
                <?php if($info['pre_teriyaki'] == 1){ ?><div class="txtcen font_04">Teriyaki</div><?php } ?>
                <?php if($info['comentarios'] != ""){ ?><div style="padding-top: 10px" class="txtcen font_02"><?php echo $info['comentarios']; ?></div><?php } ?>
                
            </div>
            <?php } ?>            
            <div class="contacto padding_01 borbottom">
                <div class="txtcen font_02"><?php echo $info['puser']['nombre']; ?></div>
                <div class="txtcen font_03"><?php echo $info['puser']['telefono']; ?></div>
                <?php 
                    if($despacho == 0){
                ?>
                    <div class="txtcen font_03 strong pddtop_01">Retiro Local <?php echo $info['local']; ?></div>
                <?php } ?>
                    
                <?php 
                    if($despacho == 1){
                    
                ?>
                <div class="txtcen font_03 strong pddtop_01">Despacho a Domicilio</div>
                <div class="txtcen font_03"><?php echo $pep['calle']; ?> <?php echo $pep['num']; ?> <?php if($pep['depto'] != ""){ ?>Depto: <?php echo $pep['depto']; ?><?php } ?></div>
                <div class="txtcen font_04"><?php echo $pep['comuna']; ?></div>
                <?php } ?>
            </div>
            <div class="total padding_01 borbottom">
                <?php if($costo > 0){ ?><div class="txtcen font_04">Costo Despacho: $<?php echo number_format($costo, 0, '', '.');; ?></div><?php } ?>
                <div class="txtcen font_06 strong">Total: $<?php echo number_format($total, 0, '', '.'); ?></div>
            </div>
        </div>
        
    </body>
</html>

<?php }else{
    
    echo "<div style='display: block; text-align:center; padding-top: 200px; font-size: 28px'>NO SE ENCONTRO EL PEDIDO<div>";
    
}} ?>