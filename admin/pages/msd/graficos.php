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
$list = $core->get_graficos_giro();
/* CONFIG PAGE */
$titulo = "Graficos";
$titulo_list = "Estadisticas";
$sub_titulo = "Configurar Grafico";
/* CONFIG PAGE */

$id = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$locales = $core->get_locales();

?>
<script>

    function cambiar_tipo(that){
        if(that.value == 1){
            navlink('pages/msd/graficos_lista.php');
        }else{
            navlink('pages/msd/graficos_lista.php?id_set='.that.value);
        }
    }
    
    function stats(that){
        
        var locales = new Array();  
        $(that).parents('form').find('.local').each(function(){
            if($(this).is(':checked')){
                locales.push({id_loc: $(this).attr('id'), nombre: $(this).attr('nombre')});
            }
        });
        
        var from = $('#fecha_from').val();
        var to = $('#fecha_to').val();
        var tipo = $('#tipo').val();
        var send = { accion: 'get_stats', locales: JSON.stringify(locales), from: from, to: to, tipo: tipo };
        
        $.ajax({
            url: "../ajax/",
            type: "POST",
            data: send,
            success: function(data){

                $('.cont_container').html("");
                for(var i=0; i<data.length; i++){
                    var Div = document.createElement('div');
                    Div.className = 'graph_container';
                    Div.setAttribute('id', 'container'+i);
                    $('.cont_container').append(Div);
                }
                for(var i=0; i<data.length; i++){
                    Highcharts.chart('container'+i, data[i]);
                }

            },
            error: function(e){}
        });

    }

    $(document).ready(function(){
         
        var dateFormat = "mm/dd/yy";
        var from = $("#fecha_from").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd'
        }).on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
        })
        var to = $("#fecha_to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd'
        }).on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
        });
 
        function getDate(element){
            var date;
            try{
                date = $.datepicker.parseDate( dateFormat, element.value );
            }catch(error){
                date = null;
            }
            return date;
        }
        
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Tipo de Grafico:</p></span>
                        <select id="tipo" onchange="cambiar_tipo(this)">
                            <option value="0">Todos</option>
                            <option value="1">Crear Nueva Lista</option>
                        <?php for($i=0; $i<count($list); $i++){ ?>
                            <option value="<?php $list[$i]['id_set']; ?>"><?php $list[$i]['nombre']; ?></option>
                        <?php } ?>
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
                    <?php if(count($locales) > 1){ ?>
                        <div class="div_locales">
                            <?php for($i=0; $i<count($locales); $i++){ ?>
                            <label class="clearfix">
                                <span><p><?php echo $locales[$i]['nombre']; ?>:</p></span>
                                <input id="<?php echo $locales[$i]['id_loc']; ?>" nombre="<?php echo $locales[$i]['nombre']; ?>" type="checkbox" class="checkbox local" value="1" <?php if($i == 0){ echo "checked='checked'"; } ?>>
                            </label>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if(count($locales) == 1){ ?>
                        <div class="div_locales">
                            <label class="clearfix" style="display: none">
                                <span><p><?php echo $locales[0]['nombre']; ?>:</p></span>
                                <input id="<?php echo $locales[0]['id_loc']; ?>" nombre="<?php echo $locales[0]['nombre']; ?>" type="checkbox" class="checkbox local" value="1" checked="checked">
                            </label>
                        </div>
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

            <div class="cont_container">


            </div>
            
            
            
        </div>
    </div>
</div>