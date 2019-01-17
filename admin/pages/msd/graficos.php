<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();

/* CONFIG PAGE */
$titulo = "Graficos";
$titulo_list = "Estadisticas";
$sub_titulo = "Configurar Grafico";
/* CONFIG PAGE */

$id = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$locales = $fireapp->get_locales();

?>
<script>

    function stats(that){
        
        var locales = new Array();  
        $(that).parents('form').find('input').each(function(){
            if($(this).attr('type') == "checkbox" && $(this).is(':checked')){
                locales.push({id_loc: $(this).attr('id'), nombre: $(this).attr('nombre')});
            }
        });
        
        var from = $('#fecha_from').val();
        var to = $('#fecha_to').val();
        var tipo = $('#tipo').val();
        var send = { accion: 'get_stats', locales: JSON.stringify(locales), from: from, to: to, tipo: tipo };
        
        $.ajax({
            url: "ajax/stats.php",
            type: "POST",
            data: send,
            success: function(data){
                console.log(data);
                Highcharts.chart('container', data);
            },
            error: function(e){}
        });

    }

    $(document).ready(function(){
         
        var dateFormat = "mm/dd/yy";
        var from = $( "#fecha_from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd'
        }).on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
        })
        var to = $( "#fecha_to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd'
        }).on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
        });
 
        function getDate( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }
            return date;
        }
        
    });

</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="backurl()"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Tipo de Grafico:</p></span>
                        <select id="tipo">
                            <option value="0">Ventas Totales</option>
                            <option value="1">Cantidad de Despachos Domicilio</option>
                            <option value="2">Cantidad Retiro Local</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Fecha Inicio:</p></span>
                        <input id="fecha_from" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Fecha Fin:</p></span>
                        <input id="fecha_to" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <?php for($i=0; $i<count($locales); $i++){ ?>
                    <label class="clearfix">
                        <span><p><?php echo $locales[$i]['nombre']; ?>:</p></span>
                        <input id="<?php echo $locales[$i]['id_loc']; ?>" nombre="<?php echo $locales[$i]['nombre']; ?>" type="checkbox" class="checkbox" value="1">
                    </label>
                    <?php } ?>
                    <label>
                        <div class="enviar"><a onclick="stats(this)">Enviar</a></div>
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
            <div id="container" style="height: 300px; display: block; padding-top: 40px">
                
            </div>
        </div>
    </div>
</div>