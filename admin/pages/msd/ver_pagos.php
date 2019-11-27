<?php

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

echo "<pre>";
print_r($list);
echo "</pre>";

/* CONFIG PAGE */
$titulo = "Pagos de ".$list['dominio'];
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

    function cambiar_meses(){
        var monto = <?php echo $list['monto']; ?>;
        var meses = $('#meses').val();
        if(meses == 1){
            $('#monto').val(monto);
            $('#monto2').val(parseInt(monto * <?php echo $iva; ?>));
        }
        if(meses == 6){
            $('#monto').val(monto * 5.5);
            $('#monto2').val(parseInt(monto * 5.5 * <?php echo $iva; ?>));
        }
        if(meses == 12){
            $('#monto').val(monto * 10);
            $('#monto2').val(parseInt(monto * 10 * <?php echo $iva; ?>));
        }
    }
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
                        <input id="fecha" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Meses:</p></span>
                        <select id="meses" onchange="cambiar_meses()">
                            <option value="1">1</option>
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
            <div class="factura">Monto: <strong><?php echo $pago["monto"]; ?></strong></div>
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
                        <div class="nombre">#<?php echo $value['factura']; ?> - <?php echo date("d-m-Y", strtotime($value['fecha'])); ?> - <?php echo $value['monto']; ?></div>
                        <a class="icono ic19" onclick="navlink('<?php echo $page_ver_pagos_giro; ?>?id_pago=<?php echo $value["id_pago"]; ?>&id_gir=<?php echo $_GET["id_gir"]; ?>&back=<?php echo $_GET["back"]; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
