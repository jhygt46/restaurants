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
    die("Error: su sesion ha expirado");
}

$list = $core->get_pagos_giros($_GET["id_gir"]);
$iva = 1.19;

$fechainicial = new DateTime($list['fecha_dns']);
$fechafinal = new DateTime();
$diferencia = $fechainicial->diff($fechafinal);
$meses = ( $diferencia->y * 12 ) + $diferencia->m;
$diff_pago = $meses - count($list['pagos']);

/* CONFIG PAGE */
$titulo = "Pagos de Proveedores";
$titulo_list = "Lista de Pagos";
$sub_titulo = "Ingresar Pago";
$accion = "crear_pago";

$id_list = "id_gir";
$eliminarobjeto = "Empresa";
$page_ver_pagos_giro = "pages/msd/ver_pagos.php";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$next_factura = 0;

if(isset($_GET["id_pago"])){
    $pago = $core->get_pago($_GET["id_pago"]);
}else{
    $next_factura = $core->next_factura();
}

?>
<script>

    $(document).ready(function(){
         
         var dateFormat = "yy-mm-dd";
         var from = $( "#fecha" ).datepicker({
             changeMonth: true,
             numberOfMonths: 1,
             dateFormat: 'yy-mm-dd'
         });

    });

</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <?php if($_GET["back"] == 1){ ?><li class="back" onclick="navlink('pages/msd/giros.php')"></li><?php } ?>
            <?php if($_GET["back"] == 2){ ?><li class="back" onclick="navlink('pages/msd/pagos.php')"></li><?php } ?>
        </ul>
    </div>
    <hr>
    <?php if($diff_pago > 0){ ?>
    <div class="cont_pagina">
        <div class="cont_pag" style="background: #fdd">
            <div class="factura">Meses Deuda: <strong style="color: #900"><?php echo $diff_pago; ?></strong></div>
            <div class="factura">Monto Deuda: <strong style="color: #900">$<?php echo number_format($list['monto'], 0, '', '.'); ?></strong></div>
            <div class="factura">Total Deuda: <strong style="color: #900">$<?php echo number_format(intval($diff_pago * $list['monto']), 0, '', '.'); ?></strong></div>
        </div>
    </div>
    <?php } ?>
    <?php if(!isset($_GET["id_pago"])){ ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <form action="" method="post">
                <div class="form_titulo clearfix">
                    <div class="titulo"><?php echo $sub_titulo; ?></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <fieldset class="<?php echo $class; ?>">
                    <input id="id_gir" type="hidden" value="<?php echo $_GET["id_gir"]; ?>" />
                    <input id="back" type="hidden" value="<?php echo $_GET["back"]; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Numero de Factura:</p></span>
                        <input id="factura" class="inputs" type="text" value="<?php echo $next_factura; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Fecha:</p></span>
                        <input id="fecha" class="inputs" type="text" value="<?php echo date("Y-m-d"); ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Meses:</p></span>
                        <select id="meses" onchange="cambiar_meses()">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="12">12</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Monto Bruto:</p></span>
                        <input id="monto" class="inputs" type="text" value="<?php echo $list['monto']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Monto Neto:</p></span>
                        <input id="monto2" class="inputs" type="text" value="<?php echo intval($list['monto'] * $iva); ?>" require="" placeholder="" />
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="factura">Factura: <strong>#<?php echo $pago["factura"]; ?></strong></div>
            <div class="factura">Fecha: <strong><?php echo date("d-m-y", strtotime($pago["fecha"])); ?></strong></div>
            <div class="factura">Monto: <strong>$<?php echo number_format($pago["monto"], 0, '', '.'); ?></strong></div>
            <?php if($pago["meses"] > 1){ ?>
            <div class="factura">Meses: <strong><?php echo $pago["meses"]; ?></strong></div>
            <?php } ?>
            <?php if($pago["meses"] == 1){ ?>
            <div class="factura">Mes: <strong>1</strong></div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo"><h1><?php echo $titulo_list; ?></h1></div>
                <ul class="opts clearfix">
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php
                foreach($list['pagos'] as $value){
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre">#<?php echo $value['factura']; ?> - <?php echo date("Y-m-d", strtotime($value['fecha'])); ?> - $<?php echo number_format($value['monto'], 0, '', '.'); ?></div>
                        <a class="icono ic19" onclick="navlink('<?php echo $page_ver_pagos_giro; ?>?id_pago=<?php echo $value["id_pago"]; ?>&id_gir=<?php echo $_GET["id_gir"]; ?>&back=<?php echo $_GET["back"]; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
