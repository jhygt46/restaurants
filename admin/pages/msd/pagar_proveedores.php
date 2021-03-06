<?php

date_default_timezone_set('America/Santiago');

if(!isset($url)){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
    $url = url();
    require_once $url["dir"]."admin/class/core_class_prod.php";
    $core = new Core();
}

// SOLO ADMIN
if($core->id_user == 0){
    die("Error: su sesion ha expirado");
}

$list = $core->get_pagos_proveedores();
$proveedores = $core->get_proveedores();

/*
$fechainicial = new DateTime($list['fecha_dns']);
$fechafinal = new DateTime();
$diferencia = $fechainicial->diff($fechafinal);
$meses = ( $diferencia->y * 12 ) + $diferencia->m;
*/

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
    </div>
    <hr>
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
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Monto:</p></span>
                        <input id="monto" class="inputs" type="text" value="<?php echo $next_factura; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Fecha:</p></span>
                        <input id="fecha" class="inputs" type="text" value="<?php echo date("Y-m-d"); ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Proveedor:</p></span>
                        <select id="id_user">
                            <?php for($i=0; $i<count($proveedores); $i++){ ?>
                            <option value="<?php echo $proveedores[$i]["id_user"]; ?>"><?php echo $proveedores[$i]["nombre"]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
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
                for($i=0; $i<count($list); $i++){
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix" style="height: 28px">
                        <div class="nombre"><?php echo $list[$i]['nombre']; ?> <?php echo date("Y-m-d", strtotime($list[$i]['fecha'])); ?> $<?php echo number_format($list[$i]['monto'], 0, '', '.'); ?></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
