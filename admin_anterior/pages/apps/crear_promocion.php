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

$that = null;
if(isset($_GET["id_cae"]) && is_numeric($_GET["id_cae"]) && $_GET["id_cae"] != 0){
    $id_cae = $_GET["id_cae"];
    $that = $fireapp->get_promocion($id_cae);
}
$arbol = $fireapp->get_arbol_productos($that);


?>
<script>
    
    $('select').on('change', function() {
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
<div class="title">
    <h1><?php echo $titulo; ?></h1>
    <ul class="clearfix">
        <li class="back" onclick="backurl()"></li>
    </ul>
</div>
<hr>
<div class="info">
    <div class="fc" id="info-0">
        <div class="minimizar m1"></div>
        <div class="close"></div>
        <div class="name"><?php echo $sub_titulo; ?></div>
        <div class="message"></div>
        <div class="sucont">

            <form action="" method="post" class="basic-grey">
                <fieldset>
                    <input id="id_cae" type="hidden" value="<?php echo $id_cae; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label>
                        <span>Precio:</span>
                        <input id="precio" type="text" value="<?php echo $that['precio']; ?>" require="" placeholder="" />
                    </label>
                    <label class="arbol">
                        <?php echo $arbol; ?>
                    </label>
                    <label style='margin-top:20px'>
                        <span>&nbsp;</span>
                        <a id='button' onclick="form()">Enviar</a>
                    </label>
                </fieldset>
            </form>
            
        </div>
    </div>
</div>
<br />
<br />