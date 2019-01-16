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
                locales.push($(this).attr('id'));
            }
        });
        
        var send = { accion: 'get_stats', locales: JSON.stringify(locales) };
        
        $.ajax({
            url: "ajax/index.php",
            type: "POST",
            data: send,
            success: function(data){
                console.log(data);
            },
            error: function(e){}
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
                        <select id="tipo"><option value="0">Categoria</option><option value="1">Promocion</option></select>
                    </label>
                    <?php for($i=0; $i<count($locales); $i++){ ?>
                    <label class="clearfix">
                        <span><p><?php echo $locales[$i]['nombre']; ?>:</p></span>
                        <input id="local-<?php echo $locales[$i]['id_loc']; ?>" type="checkbox" class="checkbox" value="1">
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