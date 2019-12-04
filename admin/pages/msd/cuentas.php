<?php

date_default_timezone_set('America/Santiago');

if(!isset($core_class_iniciada)){
    if($_SERVER["HTTP_HOST"] == "localhost"){
        define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
        define("DIR", DIR_BASE."restaurants/");
    }else{
        define("DIR_BASE", "/var/www/html/");
        define("DIR", DIR_BASE."restaurants/");
    }
    require_once DIR."admin/class/core_class_prod.php";
    $core = new Core();
}

// SOLO ADMIN
if($core->id_user == 0){
    die('<div class="pagina"><div class="title"><h1>Error: su sesion ha expirado</h1></div></div>');
}
if($core->admin == 0){
    die('<div class="pagina"><div class="title"><h1>Error: no tiene permisos</h1></div></div>');
}

$list = $core->get_giros_user();
$pap = $core->get_pago_proveedores();
if(count($pap) == 0){
    $entregado = 0;
}
if(count($pap) > 0){
    for($i=0; $i<count($pap); $i++){
        $entregado = $entregado + $pap[$i]["monto"];
    }
}

$total = 0;
$total_dns = 0;
$disponible = 0;
$eliminados = 0;
for($i=0; $i<count($list); $i++){

    if($list[$i]['prueba'] == 0){
        $total = $total + intval($list[$i]['monto_vendedor'] * 3);
        if($list[$i]['dns'] == 1){
            $total_dns = $total_dns + intval($list[$i]['monto_vendedor'] * 3);
        }
        if($list[$i]['cant_pagos'] > 0){
            $cant_pagos = ($list[$i]['cant_pagos'] > 3) ? 3 : $list[$i]['cant_pagos'] ;
            $disponible = $disponible + intval($list[$i]['monto_vendedor'] * $cant_pagos);
        }
        if($list[$i]['eliminado'] == 1){
            $cant_pagos = ($list[$i]['cant_pagos'] > 3) ? 3 : $list[$i]['cant_pagos'] ;
            $eliminados = (3 - $cant_pagos) * $list[$i]['monto_vendedor'];
        }
    }
}
$disponible = $disponible - $entregado;
$deuda = $total - $entregado - $eliminado;
$deuda_dns = $total_dns - $entregado - $eliminado;


/* CONFIG PAGE */
$titulo = "Cuentas";
/* CONFIG PAGE */



?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo"><h1>Resumen Ventas</h1></div>
                <div class="listado_items" style="45px 2% 10px 2%">
                    <div class="resumen_ventas clearfix">
                        <div class="rv1">Ventas Totales</div>
                        <div class="rv2">$<?php echo number_format($total, 0, '', '.'); ?></div>
                    </div>
                    <div class="resumen_ventas clearfix" style="padding-bottom: 20px">
                        <div class="rv1">Ventas Totales DNS</div>
                        <div class="rv2">$<?php echo number_format($total_dns, 0, '', '.'); ?></div>
                    </div>
                    <div class="resumen_ventas clearfix">
                        <div class="rv1">Pagos Entregado</div>
                        <div class="rv2">$<?php echo number_format($entregado, 0, '', '.'); ?></div>
                    </div>
                    <div class="resumen_ventas clearfix">
                        <div class="rv1">Pagos Eliminado</div>
                        <div class="rv2">$<?php echo number_format($eliminados, 0, '', '.'); ?></div>
                    </div>
                    <div class="resumen_ventas clearfix" style="padding-bottom: 20px">
                        <div class="rv1">Pago Disponible</div>
                        <div class="rv2">$<?php echo number_format($disponible, 0, '', '.'); ?></div>
                    </div>
                    <div class="resumen_ventas clearfix">
                        <div class="rv1">Total Deuda</div>
                        <div class="rv2">$<?php echo number_format($deuda, 0, '', '.'); ?></div>
                    </div>
                    <div class="resumen_ventas clearfix">
                        <div class="rv1">Total Deuda DNS</div>
                        <div class="rv2">$<?php echo number_format($deuda_dns, 0, '', '.'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
