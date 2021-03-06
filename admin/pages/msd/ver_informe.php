<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();

$core->is_giro();
$informe = $core->get_informe('2019-07-01', '2019-07-31');

?>
<script>
    Highcharts.chart('container_01', <?php echo json_encode($informe["chart1"]); ?>);
    Highcharts.chart('container_02', <?php echo json_encode($informe["chart2"]); ?>);
    Highcharts.chart('container_03', <?php echo json_encode($informe["chart3"]); ?>);
</script>
<div class="pagina">
    <div class="title">
        <h1>Informe de <?php echo $informe["nombre"]; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/giros.php')"></li>
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
            <div id="container_01" style="height: 200px; display: block"></div>
            <div id="container_02" style="margin-top: 10px; height: 200px; display: block"></div>
            <div id="container_03" style="margin-top: 10px; height: 200px; display: block"></div>
        </div>
    </div>
</div>