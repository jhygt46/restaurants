<?php

require_once("/var/www/html/restaurants/admin/class/core_class_prod.php");
$core = new Core();
$core->is_giro();
$informe = $core->get_informe('2019-07-01', '2019-07-31');

echo "<pre>";
print_r($informe);
echo "</pre>";

?>
<script>
    //Highcharts.chart('container', <?php echo json_encode($informe["chart1"]); ?>);
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
            <div id="container" style="height: 300px; display: block; padding-top: 40px">
                
            </div>
        </div>
    </div>
</div>