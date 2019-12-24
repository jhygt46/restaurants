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

$that = $core->get_pag_inicio();

/* CONFIG PAGE */
$titulo = "Pagina de Inicio";
$sub_titulo = "Ingresa informacion importante al inicio";
$accion = "configurar_inicio";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

$htmls[] = $that["inicio_html"];
$htmls[] = '<div style="width: 100%; min-height: 100%"><div style="font-size: 26px; color: #f00; padding: 20px 40px 0px 20px; color: #000">#TITULO</div><div style="font-size: 14px; padding: 20px 20px 20px 20px; color: #000">#DESCRIPCION</div></div>';
$htmls[] = '<div style="width: 100%; min-height: 100%"><div style="font-size: 26px; color: #f00; padding: 20px 40px 0px 20px; color: #000">#TITULO</div><div style="font-size: 14px; padding: 20px 20px 20px 20px; color: #000">#DESCRIPCION</div></div>';
$htmls[] = '<div style="width: 100%; min-height: 100%"><div style="font-size: 26px; color: #f00; padding: 20px 40px 0px 20px; color: #000">#TITULO</div><div style="font-size: 14px; padding: 20px 20px 20px 20px; color: #000">#DESCRIPCION</div></div>';

?>
<script>

    var htmls = [ <?php for($i=0; $i<count($htmls); $i++){ if($i > 0){ echo ","; } echo "'".$htmls[$i]."'"; } ?> ];
    function ver_paginas(){
        var pagina = $('#tipo').val();
        var info = htmls[pagina];
        $('#html').val(info);
    }

</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/configurar_contenido.php')"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id_pag; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Pagina:</p></span>
                        <select id="tipo" onchange="ver_paginas()">
                            <?php for($i=0; $i<count($htmls); $i++){ ?>
                            <option value="<?php echo $i; ?>"><?php if($i==0){ echo "Actual"; }else{ echo "Template ".$i; } ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>HTML:</p></span>
                        <TEXTAREA id="html"><?php echo $that["inicio_html"]; ?></TEXTAREA>
                    </label>
                    <label class="clearfix">
                        <span><p>Inicio visible:</p></span>
                        <input id="ver_inicio" type="checkbox" class="checkbox" value="1" <?php if($that['ver_inicio'] == 1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="clearfix">
                        <span><p>Seguir editando:</p></span>
                        <select id="seguir">
                            <option value="0">No</option>
                            <option value="1" <?php if($_GET['seguir'] == 1){ ?>selected<?php } ?>>Si</option>
                        </select>
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>