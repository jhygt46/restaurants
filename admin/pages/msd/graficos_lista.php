<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."restaurants/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."restaurants/");
}

require_once DIR."admin/class/core_class_prod.php";
$core = new Core();


/* CONFIG PAGE */
$titulo = "Lista Graficos";
$titulo_list = "Estadisticas";
$sub_titulo = "Configurar Grafico";
$accion = "crear_gra_lista";
/* CONFIG PAGE */

$id_set = 0;
$nombre = "";
$list = [];
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_set"]) && is_numeric($_GET["id_set"]) && $_GET["id_set"] != 0){

    $id_set = $_GET["id_set"];
    $nombre = $_GET["nombre"];
    $list = $core->get_graficos_lista($id_set);
    
}

$graficos[0]['num'] = 1;
$graficos[0]['nombre'] = 'Ventas Totales';
$graficos[1]['num'] = 2;
$graficos[1]['nombre'] = 'Ventas Totales Acumulado';
$graficos[2]['num'] = 3;
$graficos[2]['nombre'] = 'Ventas Totales A';
$graficos[3]['num'] = 4;
$graficos[3]['nombre'] = 'Ventas Totales B';
$graficos[4]['num'] = 5;
$graficos[4]['nombre'] = 'Ventas Totales C';
$graficos[5]['num'] = 6;
$graficos[5]['nombre'] = 'Ventas Totales D';
$graficos[6]['num'] = 7;
$graficos[6]['nombre'] = 'Ventas Totales E';
$graficos[7]['num'] = 8;
$graficos[7]['nombre'] = 'Ventas Totales F';
$graficos[8]['num'] = 9;
$graficos[8]['nombre'] = 'Ventas Totales G';

?>
<?php if(isset($_GET['sortable'])){ ?>
<script>
    $('.listado_items').sortable({
        stop: function(e, ui){
            var order = [];
            $(this).find('.l_item').each(function(){
                order.push($(this).attr('rel'));
            });
            var send = {accion: 'ordergralista', values: order, id_set: <?php echo $id_set; ?>};
            $.ajax({
                url: "ajax/",
                type: "POST",
                data: send
            });
        }
    });
</script>
<?php } ?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/graficos.php')"></li>
        </ul>
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
                    <input id="id" type="hidden" value="<?php echo $id_set; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $nombre; ?>" require="" placeholder="" />
                    </label>
                    <?php 
                    for($k=0; $k<count($graficos); $k++){ 
                        if(!in_array($graficos[$k]['num'], $list)){
                    ?>
                        <label class="clearfix">
                            <span><p><?php echo $graficos[$k]['nombre']; ?>:</p></span>
                            <input id="gra-<?php echo $list; ?>" type="checkbox" class="checkbox" />
                        </label>
                    <?php }} ?>
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
                    <li class="opt"><div onclick="navlink('pages/msd/graficos_lista.php?sortable=1&id_set=<?php echo $id_set; ?>&nombre=<?php echo $nombre; ?>')" class="order"></div></li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($k=0; $k<count($graficos); $k++){ 
                    if(in_array($graficos[$k]['num'], $list)){

                    $id = $list;
                    $nombre = $graficos[$k]['nombre'];

                ?>
                <div class="l_item" rel="<?php echo $id; ?>">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic6" onclick="eliminar('eliminar_gra_lista', '<?php echo $id; ?>/<?php echo $id_set; ?>/<?php echo $nombre; ?>', 'Grafico', '<?php echo $nombre; ?>')"></a>
                    </div>
                </div>
                <?php }} ?>
            </div>
        </div>
    </div>


</div>