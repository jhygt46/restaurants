<?php
session_start();

if(core_class_iniciada != 1){

    if($_SERVER['HTTP_HOST'] == "localhost"){
        $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
    }else{
        $path = "/var/www/html/restaurants/";
    }

    require_once($path."admin/class/core_class.php");
    $fireapp = new Core();

}
/* CONFIG PAGE */
$titulo_list = "Aplicaciones";
$id_list = "id_loc";
/* CONFIG PAGE */


$id_gir = 0;
$titulo = "GIRO NO SELECIONADO";
$class = ($_POST['w'] < 700) ? 'resp' : 'normal' ;

if(isset($_GET["id_gir"]) && is_numeric($_GET["id_gir"]) && $_GET["id_gir"] > 0){
    
    $id_gir = $_GET["id_gir"];
    $fireapp->is_giro($id_gir);
    
}else{

    $id_gir = $_SESSION["user"]['id_gir'];
    $fireapp->is_giro($id_gir);

}

$list = $fireapp->get_locales();
$giro = $fireapp->get_giro();
$catalogos = $fireapp->get_catalogos();
$num_cats = $giro['catalogo'];
$mis_cats = count($catalogos);
$titulo = $giro['nombre'];

$diff = $num_cats - $mis_cats;

if($diff == 0){
    // LIST TODAS
    if($num_cats == 1){
        
    }
    if($num_cats > 1){
        
    }
}
if($diff > 0){
    // OPCION CREAR
}

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
        <ul class="clearfix">
            <li class="back" onclick="backurl()"></li>
        </ul>
    </div>
    <hr>
    <?php if($diff > 0){ ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <form action="" method="post">
                <div class="form_titulo clearfix">
                    <div class="titulo">Nuevo Catalogo</div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <fieldset class="<?php echo $class; ?>">
                    <input id="id" type="hidden" value="0" />
                    <input id="accion" type="hidden" value="crear_catalogo" />
                    <label class="clearfix">
                        <span><p>Nombre del Catalogo:</p></span>
                        <input id="nombre" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
    <?php } ?>
    <?php for($i=0; $i<count($catalogos); $i++){ ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            
            <div class="lista_items">
                <div class="titulo_items"><h1><?php echo $catalogos[$i]['nombre']; ?></h1><h2>Crea el arbol de Productos para este catalogo</h2></div>
                <div class="items_list clearfix">
                    <div class="list_item"><div class="cont_item" onclick="navlink('pages/msd/categorias.php?id_cat=<?php echo $catalogos[$i]['id_cat']; ?>&nombre=<?php echo $catalogos[$i]['nombre']; ?>')"><div class="item_image"><img src="images/menuicon.png" alt="" /></div><div class="item_ttl">CARTA</div></div></div>
                    <div class="list_item"><div class="cont_item" onclick="navlink('pages/msd/preguntas.php?id_cat=<?php echo $catalogos[$i]['id_cat']; ?>&nombre=<?php echo $catalogos[$i]['nombre']; ?>')"><div class="item_image"><img src="images/menupreguntas.png" alt="" /></div><div class="item_ttl">PREGUNTAS</div></div></div>
                    <div class="list_item"><div class="cont_item" onclick="navlink('pages/msd/ingredientes.php?id_cat=<?php echo $catalogos[$i]['id_cat']; ?>&nombre=<?php echo $catalogos[$i]['nombre']; ?>')"><div class="item_image"><img src="images/menuingredientes.png" alt="" /></div><div class="item_ttl">LISTA<br>INGREDIENTES</div></div></div>
                </div>
            </div>
            
        </div>
    </div>
    <?php } ?>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo" onclick="navlink('pages/msd/locales.php')"><h1>LOCALES</h1><h2>Todos los locales que tiene tu negocio</h2></div>
                <ul class="opts clearfix">
                    <li class="opt" onclick="navlink('pages/msd/locales.php')">AGREGAR/MODIFICAR</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                
                for($i=0; $i<count($list); $i++){
                    $id_n = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $code = $list[$i]['code'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic5" href="../locales.php?id_loc=<?php echo $id_n; ?>" target="_self"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="lista_items">
                <div class="titulo_items"><h1>CONFIGURACION</h1><h2>Configuracion del Sistema y Sitio Web</h2></div>
                <div class="items_list clearfix">
                    <div class="list_item"><div class="cont_item" onclick="navlink('pages/msd/configurar_giro.php')"><div class="item_image"><img src="images/configbase.png" alt="" /></div><div class="item_ttl">Sitio Web</div></div></div>
                    <div class="list_item"><div class="cont_item" onclick="navlink('pages/msd/configurar_estilos.php')"><div class="item_image"><img src="images/configstyle.png" alt="" /></div><div class="item_ttl">Estilos</div></div></div>
                    <div class="list_item"><div class="cont_item" onclick="navlink('pages/msd/configurar_paginas.php')"><div class="item_image"><img src="images/configpages.png" alt="" /></div><div class="item_ttl">Paginas</div></div></div>
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