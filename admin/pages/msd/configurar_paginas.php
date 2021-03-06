<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
$url = url();

require_once $url["dir"]."admin/class/core_class_prod.php";
$core = new Core();


/* CONFIG PAGE */
$titulo = "Paginas del Menu";
$titulo_list = "Mis Paginas";
$sub_titulo1 = "Ingresar Pagina";
$sub_titulo2 = "Modificar Paginas";
$accion = "crear_pagina";

$eliminaraccion = "eliminar_pagina";
$id_list = "id_pag";
$eliminarobjeto = "Pagina";
$page_mod = "pages/msd/configurar_paginas.php";
/* CONFIG PAGE */

$id_pag = 0;
$html = "";
$tipo = 0;
$visible = -1;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sub_titulo = $sub_titulo1;
$list = $core->get_paginas();

if(isset($_GET["id_pag"]) && is_numeric($_GET["id_pag"]) && $_GET["id_pag"] != 0){

    $id_pag = $_GET["id_pag"];
    $that = $core->get_pagina($id_pag);
    $sub_titulo = $sub_titulo2;
    $html = $that["html"];
    $tipo = $that["tipo"];
    $visible = $that["visible"];

}

?>
<script>

    var htmls = [
        { html: true, image: true, data: '<?php echo $html; ?>' }, 
        { html: true, image: true, data: '<div style="width: 100%; min-height: 100%; background: url(/restaurants/images/paginas/#FOTO#) no-repeat; border-radius: 10px"><div style="font-size: 26px; padding-top: 15px; color: #f00; padding-left: 20px; padding-right: 70px">#TITULO</div><div style="font-size: 14px; padding-top: 15px; color: #f00; padding-left: 20px; padding-right: 117px">#DESCRIPCION</div></div>' },
        { html: true, image: true, data: '<div>BUENA ERNESTOR</div>' }, 
        { html: true, image: true, data: '<div>BUENA BUENA</div>' }
    ];
    function ver_paginas(){

        var pagina = $('#ejemplos').val();
        var info = htmls[pagina];

        if(info.html){
            $('#html').val(info.data);
            $('.divHtml').show();
        }else{
            $('.divHtml').hide();
        }
        if(info.image){
            $('.divImage').show();
        }else{
            $('.divImage').hide();
        }

    }
    function tipo_paginas(){

        var tipo = $('#tipo').val();
        if(tipo == 0){
            $('.sec_tipo0').show();
        }else{
            $('.sec_tipo0').hide();
        }

    }
<?php if(isset($_GET['sortable'])){ ?>
    $('.listado_items').sortable({
        stop: function(e, ui){
            var order = [];
            $(this).find('.l_item').each(function(){
                order.push($(this).attr('rel'));
            });
            var send = {accion: 'orderpag', values: order};
            $.ajax({
                url: "ajax/index.php",
                type: "POST",
                data: send
            });
        }
    });
<?php } ?>

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
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo" onchange="tipo_paginas()">
                            <option value="0" <?php if($tipo == 0){ echo "selected"; } ?>>Texto Libre</option>
                            <option value="1" <?php if($tipo == 1){ echo "selected"; } ?>>Locales</option>
                            <option value="2" <?php if($tipo == 2){ echo "selected"; } ?>>Contacto</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Visible:</p></span>
                        <input id="visible" type="checkbox" class="checkbox" value="1" <?php if($visible == 1 || $visible == -1){ ?>checked="checked"<?php } ?>>
                    </label>
                    <label class="sec_tipo0 clearfix" <?php if($tipo > 0){ echo "style='display: none'"; } ?>>
                        <span><p>Ejemplos:</p></span>
                        <select id="ejemplos" onchange="ver_paginas()">
                            <option value="0"><?php if($id_pag > 0){ echo "Actual"; }else{ echo "Nueva"; } ?></option>
                            <option value="1">Pagina 1</option>
                            <option value="2">Pagina 2</option>
                            <option value="3">Pagina 3</option>
                        </select>
                    </label>
                    <label class="sec_tipo0 divImage clearfix" <?php if($tipo > 0){ echo "style='display: none'"; } ?>>
                        <span><p>Imagen:</p></span>
                        <input id="file_image0" type="file" style="padding-top: 8px" />
                    </label>
                    <label class="sec_tipo0 divHtml clearfix" <?php if($tipo > 0){ echo "style='display: none'"; } ?>>
                        <span><p>HTML:</p></span>
                        <TEXTAREA id="html"><?php echo $that['html']; ?></TEXTAREA>
                    </label>
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
                    <li class="opt"><div onclick="navlink('pages/msd/configurar_paginas.php?sortable=1')" class="order"></div></li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item" rel="<?php echo $id; ?>">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_pag=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>