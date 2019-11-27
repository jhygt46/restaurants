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
if($core->id_user != 1){
    die("Error:");
}

$list = $core->get_giros_pagos();
$iva = 1.19;

/* CONFIG PAGE */
$titulo = "Pagos";
$titulo_list = "Pagos Atrasados";
$page_ver_pagos_giro = "pages/msd/ver_pagos.php";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="pagos" onclick="navlink('pages/msd/todos_los_pagos.php')"></li>
        </ul>
    </div>
    <hr>
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

                foreach($list as $clave => $value){

                    $fechainicial = new DateTime($value['fecha_dns']);
                    $fechafinal = new DateTime();
                    $diferencia = $fechainicial->diff($fechafinal);
                    $meses = ( $diferencia->y * 12 ) + $diferencia->m;
                    $diff_pago = $meses - $value['cpagos'];

                    if($diff_pago > 0){
                        
                        if($diff_pago == 1){ $mensaje = '( 1 mes )'; }
                        if($diff_pago > 1){ $mensaje = '( '.$diff_pago.' meses )'; }

                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre" style="font-weight: bold"><?php echo $value['dominio']; ?> <?php echo $mensaje; ?></div>
                        <a class="icono ic18" onclick="navlink('<?php echo $page_ver_pagos_giro; ?>?id_gir=<?php echo $clave; ?>&back=2')"></a>
                    </div>
                </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</div>
