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

$list = $core->ver_todos_los_pagos();

if(isset($_GET["id_pago"])){
    $pago = $core->get_pago($_GET["id_pago"]);
}

/* CONFIG PAGE */
$titulo = "Todos los Pagos";
$titulo_list = "Lista de Pagos";
$sub_titulo = "Ingresar Pago";
$accion = "crear_pago";

$id_list = "id_gir";
$eliminarobjeto = "Empresa";
$page_ver_pagos_giro = "pages/msd/ver_pagos.php";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/pagos.php')"></li>
        </ul>
    </div>
    <hr>
    <?php if(isset($_GET["id_pago"])){ ?>
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
                for($i=0; $i<count($list); $i++){
                    $factura = $list[$i]['factura'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre">#<?php echo $factura; ?> - <?php echo date("Y-m-d", strtotime($list[$i]['fecha_dns'])); ?></div>
                        <a class="icono ic18" onclick="navlink('<?php echo $page_ver_pagos_giro; ?>?id_gir=<?php echo $clave; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
