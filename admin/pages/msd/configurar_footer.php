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

$footer = $core->get_footer();

/* CONFIG PAGE */
$titulo = "Bajada";
$sub_titulo = "Selecciona tu bajada favorita";
$accion = "configurar_footer";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

?>
<script>

    var htmls = [
        { data: '' }, 
        { data: '<div style="width: 100%; min-height: 100%; border-radius: 10px"><div style="font-size: 26px; padding-top: 15px; color: #f00; padding-left: 20px; padding-right: 70px">#TITULO</div><div style="font-size: 14px; padding-top: 15px; color: #f00; padding-left: 20px; padding-right: 117px">#DESCRIPCION</div></div>' },
        { data: '<div>BUENA ERNESTOR</div>' }, 
        { data: '<div>BUENA BUENA</div>' }
    ];

    function ver_paginas(){
        var pagina = $('#tipo').val();
        var info = htmls[pagina];
        $('#html').val(info.data);
        $('.view_html').html(info.data);
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
                            <option value="0">Nueva</option>
                            <option value="1">Bajada 1</option>
                            <option value="2">Bajada 2</option>
                            <option value="3">Bajada 3</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>HTML:</p></span>
                        <TEXTAREA id="html"><?php echo $footer; ?></TEXTAREA>
                    </label>
                    <label class="clearfix">
                        <span><p>Preview:</p></span>
                        <div class="perfil_preguntas view_html" style="background: #fff; min-height: 60px"><?php echo $footer; ?></div>
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