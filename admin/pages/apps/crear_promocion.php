<?php
session_start();

require_once("../../class/core_class.php");
require_once("../../idioma/es-CL.php");
$fireapp = new Core();
//$fireapp->seguridad_exit(array(48));

$titulo = "Productos";
$titulo_list = "Productos de la Promocion";
$sub_titulo1 = "Seleccionar Productos";
$accion = "asignar_prods_promocion";

$eliminaraccion = "eliminar_promocion";
$id_list = "id_prm";
$eliminarobjeto = "Promocion";
$page_mod = "pages/apps/crear_promocion.php";
/* CONFIG PAGE */

$id = 0;
$id_prm = 0;
$sub_titulo = $sub_titulo1;

if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] != 0){
    
    
    $id = $_GET["id"];
    $parent_id = $_GET["parent_id"];
    $that = null;
    if(isset($_GET["id_prm"]) && is_numeric($_GET["id_prm"]) && $_GET["id_prm"] != 0){
        $id_prm = $_GET["id_prm"];
        $that = $fireapp->get_promocion($id_prm);
    }
    $arbol = $fireapp->get_arbol_productos($id, $that);
    
}



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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="id_prm" type="hidden" value="<?php echo $id_prm; ?>" />
                    <input id="parent_id" type="hidden" value="<?php echo $parent_id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="arbol">
                        <?php echo $arbol; ?>
                    </label>
                    <label>
                        <span>Precio:</span>
                        <input id="precio" type="text" value="<?php echo $that['precio']; ?>" require="" placeholder="" />
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