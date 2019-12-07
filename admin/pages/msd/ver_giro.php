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

$dominio = "www.buenanelson.com";
$dom_mail = explode(".", $dominio);
$locales[] = crear_array_locales('+56966166923', '+56966166923', 'Local 1', '1', 'Avda Manuel Montt 1256, Santiago, Chile', -33.438931, -70.615707, 'local1@'.$dom_mail[1].'.'.$dom_mail[2]);
$locales[] = crear_array_locales('+56966166923', '+56966166923', 'Local 2', '1', 'Avda Los Leones 1245, Providencia, Chile', -33.430810, -70.602630, 'local2@'.$dom_mail[1].'.'.$dom_mail[2]);
$this->crear_locales_prueba($locales, 1, 1);
exit;


$core->is_giro();

// SOLO GIROS
if($core->id_user == 0){
    die('<div class="pagina"><div class="title"><h1>Error: su sesion ha expirado</h1></div></div>');
}
if($core->id_gir == 0){
    die('<div class="pagina"><div class="title"><h1>Error: No hay Giro Asignado</h1></div></div>');
}

$titulo_list = "Aplicaciones";
$id_list = "id_loc";
$titulo = "GIRO NO SELECIONADO";
$class = ($_POST['w'] < 700) ? 'resp' : 'normal' ;

$pagos = $core->get_pagos();
$giro = $core->get_giro();

$fechainicial = new DateTime($pagos['fecha_dns']);
$fechafinal = new DateTime();
$diferencia = $fechainicial->diff($fechafinal);
$meses = ( $diferencia->y * 12 ) + $diferencia->m;
$diff_pago = $meses - $pagos['cpagos'];

if($giro['dns'] == 0){
    
    $data['test'] = 'Dw7k2s_hKi5sqPs8';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://'.$giro['dominio']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
    $resp = curl_exec($ch);
    curl_close($ch);
    if($resp == 'hjS3r%mDs-5gYa6ib_5Ps'){
        $core->set_giro_dns();
    }

}

$catalogos = $core->get_catalogos();
$num_cats = $giro['catalogo'];
$mis_cats = count($catalogos);
$titulo = ($giro['nombre'] != '') ? 'Bienvenido '.$giro['nombre'] : 'Bienvenido '.$giro['dominio'] ;
$diff = $num_cats - $mis_cats;

?>
<script>
$(document).ready(function(){
    init_chart();
});
function init_chart(){

    Highcharts.chart('grafico_inicio', {
        chart: {
            type: 'area',
            backgroundColor: '#dddddd'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio'],
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: ''
            },
            labels: {
                formatter: function () {
                    return this.value / 1000;
                }
            }
        },
        tooltip: {
            split: true,
            valueSuffix: ' millions'
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 0,
                marker: {
                    lineWidth: 0,
                    lineColor: '#666666'
                }
            }
        },
        series: [{
            showInLegend: false,
            name: 'Europe',
            data: [2, 4, 8, 16, 32, 64, 128]
        }, {
            showInLegend: false,
            name: 'America',
            data: [1.9, 3.6, 6.8, 13, 24.7, 47, 89.3]
        }, {
            showInLegend: false,
            name: 'Oceania',
            data: [1.8, 3.2, 5.8, 10.5, 18.9, 34, 61.2]
        }]
    });

}
</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
    </div>
    <hr>
    <?php if($diff_pago > 0){ $monto = $diff_pago * $pagos['monto']; ?>
    <div class="cont_pagina" style="background: #a66; cursor: pointer" onclick="navlink('pages/msd/renovar_servicio.php')">
        <div class="cont_pag" style="background: #edd">
            <div class="lista_items">
                <div class="titulo_items" style="padding-bottom: 0px"><h1 style="color: #900; font-size: 26px">Aviso! Renovacion de Servicio</h1><h2 style="color: #900; font-size: 18px">Si desea renovar su servicio con nosotros, haga click aca</h2></div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php for($i=0; $i<count($catalogos); $i++){ ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items"><h1><?php echo $catalogos[$i]['nombre']; ?></h1><h2>Crea el arbol de Productos para este catalogo</h2></div>
                <div class="items_list clearfix">
                    <div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/categorias.php?id_cat=<?php echo $catalogos[$i]['id_cat']; ?>&nombre=<?php echo $catalogos[$i]['nombre']; ?>')"><div class="item_image"><img src="images/menuicon.png" alt="" /></div><div class="item_ttl">CARTA</div></div></div>
                    <div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/preguntas.php?id_cat=<?php echo $catalogos[$i]['id_cat']; ?>&nombre=<?php echo $catalogos[$i]['nombre']; ?>')"><div class="item_image"><img src="images/menupreguntas.png" alt="" /></div><div class="item_ttl">PREGUNTAS</div></div></div>
                    <div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/ingredientes.php?id_cat=<?php echo $catalogos[$i]['id_cat']; ?>&nombre=<?php echo $catalogos[$i]['nombre']; ?>')"><div class="item_image"><img src="images/menuingredientes.png" alt="" /></div><div class="item_ttl">LISTA<br>INGREDIENTES</div></div></div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items"><h1>Sitio Web</h1><?php if($giro["dns"] == 0){ ?><h2>Sube tu sitio ahora mismo</h2><?php }else{ ?><h2>Agregale seguridad a tu sitio web</h2><?php } ?></div>
                <div class="items_list clearfix">
                    <div class="list_item wi_01"><div class="cont_item"><a href="<?php if($giro["dns"] == 0){ ?>http://<?php echo $giro["ip"]; ?>/view/<?php echo $giro["dominio"]; ?><?php }else{ ?>http<?php if($giro["ssl"] == 1){ echo "s"; } ?>://<?php echo $giro["dominio"]; ?><?php } ?>" target="_blank" style="text-decoration: none"><div class="item_image"><img src="images/web_temp.png" alt="" /></div><div class="item_ttl"><?php if($giro["dns"] == 0){ ?>SITIO TEMPORAL<?php }else{ ?>VISITAR SITIO<?php } ?></div></a></div></div>
                    <?php if($giro["dns"] == 0 && $giro["dns_letra"] !== NULL){ ?><div class="list_item wi_02"><div class="dns_item"><div class="cont_dns"><?php for($i=1; $i<=4; $i++){ ?><h1>ns-cloud-<?php echo $giro["dns_letra"].$i; ?>.googledomains.com</h1><?php } ?></div></div><div class="dns_info">CAMBIA TUS DNS</div></div><?php } ?>
                    <?php if($giro["dns"] == 1 && $giro["ssl"] == 0){ ?><div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/https.php')"><div class="item_image"><img src="images/https.png" alt="" /></div><div class="item_ttl">SEGURIDAD HTTPS</div></div></div><?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag" onclick="navlink('pages/msd/locales.php')" style="cursor: pointer">
            <div class="list_titulo" style="height: 120px">
                <div class="valign" style="left: 10px"><h1 style="font-size: 30px">LOCALES</h1><h2 style="font-size: 20px">Todos los locales que tiene tu negocio</h2></div>
                <div class="valign" style="right: 10px"><img src="images/locales_icon.png" alt="" /></div>
            </div>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items"><h1>CONFIGURACION</h1><h2>Configuracion del Sistema y Sitio Web</h2></div>
                <div class="items_list clearfix">
                    <div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/configurar_giro.php')"><div class="item_image"><img src="images/configbase.png" alt="" /></div><div class="item_ttl">Configuracion</div></div></div>
                    <div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/configurar_estilos.php')"><div class="item_image"><img src="images/configstyle.png" alt="" /></div><div class="item_ttl">Estilos</div></div></div>
                    <div class="list_item wi_01"><div class="cont_item" onclick="navlink('pages/msd/configurar_contenido.php')"><div class="item_image"><img src="images/configpages.png" alt="" /></div><div class="item_ttl">Contenido</div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items_graficos">
                <div class="titulo_items_graficos valign" onclick="navlink('pages/msd/graficos.php')"><h1>Graficos</h1><h2>Toda la informacion de tus ventas</h2></div>
                <div class="grafico valign" id="grafico_inicio"></div>
            </div>
        </div>
    </div>
</div>