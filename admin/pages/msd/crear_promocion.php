<?php
session_start();

if($_SERVER['HTTP_HOST'] == "localhost"){
    $path = $_SERVER['DOCUMENT_ROOT']."/restaurants/";
}else{
    $path = "/var/www/html/restaurants/";
}

require_once($path."admin/class/core_class.php");
$fireapp = new Core();

$titulo = "Promociones";
$sub_titulo1 = "Seleccionar Categorias y/o Productos";
$accion = "asignar_prods_promocion";
/* CONFIG PAGE */

$id_cae = 0;
$sub_titulo = $sub_titulo1;
$parent_id = (isset($_GET["parent_id"]))? $_GET["parent_id"] : 0 ;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

$that = null;
if(isset($_GET["id_cae"]) && is_numeric($_GET["id_cae"]) && $_GET["id_cae"] != 0){
    $id_cae = $_GET["id_cae"];
    $that = $fireapp->get_promocion($id_cae);
    
    echo "<pre>";
    print_r($that);
    echo "</pre>";
    
}
$arbol = $fireapp->get_arbol_productos($that);


?>
<script>
    
    $('select').on('change', function(){
        
        var valor = this.value;
        if(valor == 0){
            var parent = $(this).parent().parent().parent().find('.left_arbol');
            parent.slideDown();
        }
        if(valor > 0){
            var parent = $(this).parent().parent().parent().find('.left_arbol');
            parent.slideUp();
        }
        
    });
    
</script>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/msd/categorias.php?parent_id=<?php echo $parent_id; ?>')"></li>
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
                    <input id="id_cae" type="hidden" value="<?php echo $id_cae; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Precio:</p></span>
                        <input id="precio" type="text" class="inputs" value="<?php echo $that['precio']; ?>" require="" placeholder="" />
                    </label>
                    <div class="arbol">
                        <div class="cont_arbol">
                            <?php echo $arbol; ?>
                        </div>
                    </div>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>