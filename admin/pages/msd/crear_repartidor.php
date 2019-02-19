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
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$sub_titulo = $sub_titulo1;
$list = $fireapp->get_paginas();

if(isset($_GET["id_pag"]) && is_numeric($_GET["id_pag"]) && $_GET["id_pag"] != 0){

    $id_pag = $_GET["id_pag"];
    $that = $fireapp->get_pagina($id_pag);
    $sub_titulo = $sub_titulo2;

}

?>
<script>

    var htmls = [
        { html: true, image: true, data: '' }, 
        { html: true, image: true, data: '<div style="width: 100%; min-height: 100%; background: url(/restaurants/images/paginas/#FOTO#) no-repeat; border-radius: 10px"><div style="font-size: 26px; padding-top: 15px; color: #f00; padding-left: 20px; padding-right: 70px">#TITULO</div><div style="font-size: 14px; padding-top: 15px; color: #f00; padding-left: 20px; padding-right: 117px">#DESCRIPCION</div></div>' },
        { html: true, image: true, data: '<div>BUENA ERNESTOR</div>' }, 
        { html: true, image: true, data: '<div>BUENA BUENA</div>' }
    ];
    function ver_paginas(){

        var pagina = $('#tipo').val();
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

</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/ver_giro.php')"></li>
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
                        <span><p>Pagina:</p></span>
                        <select id="tipo" onchange="ver_paginas()">
                            <option value="0">Nueva</option>
                            <option value="1">Pagina 1</option>
                            <option value="2">Pagina 2</option>
                            <option value="3">Pagina 3</option>
                        </select>
                    </label>
                    <label class="divImage clearfix">
                        <span><p>Imagen:</p></span>
                        <input id="file_image" type="file" style="padding-top: 7px" />
                    </label>
                    <label class="divHtml clearfix">
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
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item">
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